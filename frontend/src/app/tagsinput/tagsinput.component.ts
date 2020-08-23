import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {MainService} from "../main.service";

@Component({
  selector: 'app-tagsinput',
  templateUrl: './tagsinput.component.html',
  styleUrls: ['./tagsinput.component.scss']
})
export class TagsinputComponent implements OnInit {

  constructor(private service : MainService) { }

  @Input() text = {id : 0, tags : []};
  @Input() tags = [];
  @Input() modelId = null;
  newTag = '';
  tagAdding = false;
  @Output() onChange = new EventEmitter<any>();

  ngOnInit(): void {
  }

  addTag ()
  {
    this.tagAdding = true;
    this.service.addLabel(this.modelId, this.newTag).subscribe(response =>
    {
      this.tagAdding = false;
      this.tags.push(response);
    });
    this.newTag = '';
  }

  isSelect (tag)
  {
    if (this.text === null)
    {
      return false;
    }
    return this.text.tags.find(textTag => textTag.model_id === tag.id) !== undefined;
  }

  toggleSelect (tag)
  {
    if (this.isSelect(tag))
    {
      console.log(tag, this.text.tags.length);
      this.text.tags = this.text.tags.filter(textTag => {
        console.log(textTag.model_id, tag.id);
        return textTag.model_id !== tag.id;
      });
      console.log(tag, this.text.tags.length);
    }
    else
    {
      this.text.tags.push({id : tag.id, model_id : tag.id, text_id : this.text.id});
    }

    this.onChange.emit(this.text);
  }

  onDelete (tag)
  {
    if (!confirm("Удалить?"))
    {
      return;
    }

    this.service.deleteLabel(tag.id).subscribe(response =>
    {
      this.text.tags = this.text.tags.filter(textTag => {
        return textTag.model_id !== tag.id;
      });

      this.tags = this.tags.filter(textTag => {
        return textTag.id !== tag.id;
      });

    });
  }
}
