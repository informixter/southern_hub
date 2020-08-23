import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})
export class MainService {

  constructor(private httpClient : HttpClient) {

  }

  search (text)
  {
    return this.httpClient.post('http://localhost/api/models/search', {text});
  }

  getModels ()
  {
    return this.httpClient.get('http://localhost/api/models');
  }

  getTexts (idModel)
  {
    return this.httpClient.get(`http://localhost/api/models/${idModel}/texts`);
  }

  addModel (name)
  {
    return this.httpClient.post(`http://localhost/api/models`, {name});
  }


  addText (idModel, text)
  {
    return this.httpClient.post(`http://localhost/api/models/${idModel}/texts`, {text});
  }

  editText (idText, text)
  {
    return this.httpClient.put(`http://localhost/api/texts/${idText}`, {text});
  }

  getTextById (textId)
  {
    return this.httpClient.get(`http://localhost/api/texts/${textId}`);
  }

  getLabels (modelId)
  {
    return this.httpClient.get(`http://localhost/api/labels/${modelId}`);
  }

  addLabel (modelId, name)
  {
    return this.httpClient.post(`http://localhost/api/labels/${modelId}`, {name});
  }

  deleteLabel (labelId)
  {
    return this.httpClient.delete(`http://localhost/api/labels/${labelId}`);
  }


  saveLabelsForText (textId, labelsIds)
  {
    return this.httpClient.post(`http://localhost/api/texts/${textId}/labels`, {labelsIds});
  }

}
