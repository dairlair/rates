import {NgModule} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {RatesComponent} from "./pages/rates/rates.component";
import {ConverterComponent} from "./pages/converter/converter.component";

const routes: Routes = [
  {path: '', pathMatch: 'full', redirectTo: '/rates'},
  {path: 'rates', component: RatesComponent},
  {path: 'converter', component: ConverterComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {
}
