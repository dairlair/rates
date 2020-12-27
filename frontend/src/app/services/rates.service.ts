import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {Rate} from "../models/rate";
import {Observable} from "rxjs";

const baseUrl = 'http://localhost:8000/rates';

@Injectable({
  providedIn: 'root'
})
export class RatesService {

  constructor(private http: HttpClient) {
  }

  getAll(): Observable<Rate[]> {
    return this.http.get<Rate[]>(baseUrl);
  }

  update(id: any, data: any): Observable<any> {
    return this.http.put(`${baseUrl}/${id}`, data);
  }

  delete(id: any): Observable<any> {
    return this.http.delete(`${baseUrl}/${id}`);
  }
}
