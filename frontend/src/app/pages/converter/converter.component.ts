import { Component, OnInit } from '@angular/core';
import {Rate} from "../../models/rate";
import {RatesService} from "../../services/rates.service";
import {Currency} from "../../models/currency";
import {ConverterService} from "../../services/converter.service";

@Component({
  selector: 'app-converter',
  templateUrl: './converter.component.html',
  styleUrls: ['./converter.component.scss']
})
export class ConverterComponent implements OnInit {
  rates?: Rate[];
  currencies?: Record<number, Currency>;
  amount: number = 100;
  convertedAmount: number = 0;
  baseCurrencyId: number;
  quoteCurrencyId: number;
  baseCurrencies: Currency[];
  quoteCurrencies: Currency[];
  conversionMessage: String = "";

  constructor(private ratesService: RatesService) {
  }

  ngOnInit(): void {
    this.retrieveRates();
  }

  retrieveRates(): void {
    this.ratesService.getAll()
      .subscribe(data => {
        this.rates = data;
        this.currencies = this.getCurrencies();
        this.updateDropdowns();
      },error => {console.log(error)});
  }

  getCurrencies(): Record<number, Currency> {
    if (!this.rates || !this.rates.length) {
      return [];
    }
    let currencies: Record<number, Currency> = [];
    this.rates.forEach((rate: Rate) => {
      [rate.base_currency, rate.quote_currency].forEach((currency: Currency) => {
        if (currency.id && !(currency.id in currencies)) {
          currencies[currency.id] = currency;
        }
      });
    });

    return currencies;
  }

  getCurrenciesWithExclusion(excludedCurrencyId: number): Currency[]
  {
    let currencies: Currency[] = [];
    console.log('Exclude: ', excludedCurrencyId);
    for (let currencyId in this.currencies) {
      if (String(currencyId) !== String(excludedCurrencyId)) {
        currencies.push(this.currencies[currencyId]);
      }
    }

    return currencies;
  }

  baseCurrencyChanged(currencyId: number) {
    this.baseCurrencyId = currencyId;
    this.updateDropdowns();
  }

  quoteCurrencyChanged(currencyId: number) {
    this.quoteCurrencyId = currencyId;
    this.updateDropdowns();
  }

  amountChanged(amount: number) {
    this.amount = amount;
    this.updateConversion();
  }

  updateDropdowns(): void {
    this.quoteCurrencies = this.getCurrenciesWithExclusion(this.baseCurrencyId);
    this.baseCurrencies = this.getCurrenciesWithExclusion(this.quoteCurrencyId);
    this.updateConversion();
  }

  updateConversion(): void {
    if (!this.baseCurrencyId) {
      this.conversionMessage = 'Please, select the base currency';
      return;
    }
    if (!this.quoteCurrencyId) {
      this.conversionMessage = 'Please, select the quote currency';
      return;
    }
    if (!this.amount) {
      this.conversionMessage = 'Please, type the amount';
      return;
    }

    // Lets try to convert with ConverterService initialized with our loaded rates
    const converterService: ConverterService = new ConverterService(this.rates);
    let conversion = converterService.convert(this.baseCurrencyId, this.quoteCurrencyId);
    if (!conversion.length) {
      this.conversionMessage = 'Conversion is not possible';
      return;
    }

    console.log('Conversion: ', conversion);
    let conversionRate: number = 1;
    this.conversionMessage = '';
    conversion.forEach((rate: Rate) => {
      conversionRate *= rate.rate;
      this.conversionMessage += `${rate.base_currency.code}/${rate.quote_currency.code} ${rate.source.name} `;
    });

    this.convertedAmount = this.roundToXDigits(this.amount * conversionRate, 5);

    return;
  }

  roundToXDigits(value: number, digits: number) {
    return Math.round(value * Math.pow(10, digits)) / Math.pow(10, digits);
  }
}
