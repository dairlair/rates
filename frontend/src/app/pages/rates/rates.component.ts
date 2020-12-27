import { Component, OnInit } from '@angular/core';
import {RatesService} from "../../services/rates.service";
import {Rate} from "../../models/rate";

@Component({
  selector: 'app-rates',
  templateUrl: './rates.component.html',
  styleUrls: ['./rates.component.scss']
})
export class RatesComponent implements OnInit {

  rates?: Rate[];

  constructor(private ratesService: RatesService) { }

  ngOnInit(): void {
    this.retrieveRates();
  }

  retrieveRates(): void {
    this.ratesService.getAll()
      .subscribe(
        data => {
          this.rates = data;
          console.log(data);
        },
        error => {
          console.log(error);
        });
  }
}
