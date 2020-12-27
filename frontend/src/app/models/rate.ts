import {Currency} from "./currency";
import {Source} from "./source";

export class Rate {
  id: number;
  baseCurrency: Currency;
  quoteCurrency: Currency;
  source: Source;
  rate: number;
}
