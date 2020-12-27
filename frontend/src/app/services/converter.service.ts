import { Injectable } from '@angular/core';
import {Rate} from "../models/rate";

@Injectable({
  providedIn: 'root'
})
export class ConverterService {

  constructor(private rates: Rate[]) {
  }

  public convert(baseCurrencyId, quoteCurrencyId: number): Rate[]
  {
    let conversion: Rate[] = [];
    // Check the direct rates (base currency and quote currency the same as required)
    this.rates.forEach((rate: Rate) => {
      console.log('Convert', rate, baseCurrencyId, quoteCurrencyId);
      if (rate.base_currency.id == baseCurrencyId && rate.quote_currency.id == quoteCurrencyId) {
        conversion.push(rate);
        return conversion;
      }
      if (rate.quote_currency.id == baseCurrencyId && rate.base_currency.id == quoteCurrencyId) {
        conversion.push(this.revertRate(rate));
        return conversion;
      }
    });

    return conversion;
  }

  private revertRate(rate: Rate): Rate
  {
    let reveredRate: Rate = new Rate();
    reveredRate.base_currency = rate.quote_currency;
    reveredRate.quote_currency = rate.base_currency;
    reveredRate.source = rate.source;
    reveredRate.rate = 1 / rate.rate;

    return reveredRate;
  }
}
