import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class AnotherService {

  constructor(private httpClient : HttpClient) {

  }

  run (idModel)
  {
    return this.httpClient.get(`/api/api/models/${idModel}/run`);
  }

  info (idModel)
  {
    return this.httpClient.get(`/api/api/models/${idModel}/info`);
  }

  testRequest (idModel, text)
  {
    return this.httpClient.post(`/api/api/models/${idModel}/testRequest`, {text});
  }


}
