import { Component, OnInit } from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {MainService} from "../main.service";

@Component({
  selector: 'app-configure',
  templateUrl: './configure.component.html',
  styleUrls: ['./configure.component.scss']
})
export class ConfigureComponent implements OnInit {

  constructor(private route : ActivatedRoute, private service : MainService) { }

  ngOnInit(): void {
    let textId = this.route.snapshot.params.id;
  }

}
