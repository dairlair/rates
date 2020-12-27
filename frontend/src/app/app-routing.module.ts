import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {RatesComponent} from "./rates/rates.component";
import {ConverterComponent} from "./converter/converter.component";
import {IndexComponent} from "./index/index.component";

const routes: Routes = [
  { path: '', component: IndexComponent },
  { path: 'rates', component: RatesComponent },
  { path: 'converter', component: ConverterComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
