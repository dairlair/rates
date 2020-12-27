import {Currency} from "./currency";
import {Source} from "./source";

export class Rate {
  id: number;
  base_currency: Currency;
  quote_currency: Currency;
  source: Source;
  rate: number;
}
