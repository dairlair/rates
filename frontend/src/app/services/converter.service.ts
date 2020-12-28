import { Injectable } from '@angular/core';
import {Rate} from "../models/rate";

@Injectable({
  providedIn: 'root'
})
export class ConverterService {

  constructor(private rates: Rate[]) {
  }

  public convert(baseCurrencyId, quoteCurrencyId: number, depth: number = 0): Rate[]
  {
    for (let i in this.rates) {
      const directRate = this.rates[i];
      const revertedRate = ConverterService.revertRate(directRate);
      // Check the direct/reverted rates (base currency and quote currency the same as required)
      const consideredRates = [directRate, revertedRate];
      for (let j in consideredRates) {
        const rate = consideredRates[j];
        if (ConverterService.isSuiteRate(rate, baseCurrencyId, quoteCurrencyId)) {
          return [rate];
        }
      }
    }

    // No way for direct conversion, lets try to find other way, using recursion
    // Should be moved to settings.
    if (depth > 3) {
      return [];
    }

    for (let i in this.rates) {
      const directRate = this.rates[i];
      const revertedRate = ConverterService.revertRate(directRate);
      // Check the direct/reverted rates (base currency and quote currency the same as required)
      const consideredRates = [directRate, revertedRate];
      for (let j in consideredRates) {
        const rate = consideredRates[j];
        if (rate.base_currency.id == baseCurrencyId) {
          let crossConversion: Rate[] = this.convert(rate.quote_currency.id, quoteCurrencyId, depth + 1);
          if (crossConversion.length > 0) {
            return [].concat([rate], crossConversion);
          }
        }
      }
    }

    return [];
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

  private static isSuiteRate(rate: Rate, baseCurrencyId, quoteCurrencyId: number): boolean
  {
    return rate.base_currency.id == baseCurrencyId && rate.quote_currency.id == quoteCurrencyId;
  }
}
