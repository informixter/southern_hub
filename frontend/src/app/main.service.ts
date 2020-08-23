import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class MainService {

  constructor(private httpClient : HttpClient) {

  }

  private httpLocalhostApiModelsSearch = '/api';

  search (text)
  {
    return this.httpClient.post(this.httpLocalhostApiModelsSearch + '/api/models/search', {text});
  }

  getModels ()
  {
    return this.httpClient.get('/api/api/models');
  }

  getTexts (idModel)
  {
    return this.httpClient.get(`/api/api/models/${idModel}/texts`);
  }

  addModel (name)
  {
    return this.httpClient.post(`/api/api/models`, {name});
  }


  addText (idModel, text)
  {
    return this.httpClient.post(`/api/api/models/${idModel}/texts`, {text});
  }

  editText (idText, text)
  {
    return this.httpClient.put(`/api/api/texts/${idText}`, {text});
  }

  getTextById (textId)
  {
    return this.httpClient.get(`/api/api/texts/${textId}`);
  }

  getLabels (modelId)
  {
    return this.httpClient.get(`/api/api/labels/${modelId}`);
  }

  addLabel (modelId, name)
  {
    return this.httpClient.post(`/api/api/labels/${modelId}`, {name});
  }

  deleteLabel (labelId)
  {
    return this.httpClient.delete(`/api/api/labels/${labelId}`);
  }


  saveLabelsForText (textId, labelsIds)
  {
    return this.httpClient.post(`/api/api/texts/${textId}/labels`, {labelsIds});
  }

}
