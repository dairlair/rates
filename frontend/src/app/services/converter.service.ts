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
      if (rate.base_currency.id == baseCurrencyId && rate.quote_currency.id == quoteCurrencyId) {
        conversion.push(rate);
        return conversion;
      }
      if (rate.quote_currency.id == baseCurrencyId && rate.base_currency.id == quoteCurrencyId) {
        conversion.push(ConverterService.revertRate(rate));
        return conversion;
      }
    });

    // No way for direct conversion, lets try to find other way, using recursion
    this.rates.forEach((rate: Rate) => {
      if (rate.base_currency.id == baseCurrencyId) {
        let crossConversion: Rate[] = this.convert(rate.quote_currency.id, quoteCurrencyId);
        if (crossConversion.length > 0) {
          console.log('Cross conversion: ', crossConversion);
          conversion = conversion.concat([rate], crossConversion);
        }
      }
    });

    return conversion;
  }

  private static revertRate(rate: Rate): Rate
  {
    let reveredRate: Rate = new Rate();
    reveredRate.base_currency = rate.quote_currency;
    reveredRate.quote_currency = rate.base_currency;
    reveredRate.source = rate.source;
    reveredRate.rate = 1 / rate.rate;

    return reveredRate;
  }
}
