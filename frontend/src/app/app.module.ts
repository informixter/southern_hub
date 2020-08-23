import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HeaderComponent } from './header/header.component';
import { TextinputComponent } from './textinput/textinput.component';
import { TagsinputComponent } from './tagsinput/tagsinput.component';
import { ConfigureComponent } from './configure/configure.component';
import {MatCardModule} from "@angular/material/card";
import {MatFormFieldModule} from "@angular/material/form-field";
import {MatIconModule} from "@angular/material/icon";
import {MatInputModule} from "@angular/material/input";
import {FormsModule} from "@angular/forms";
import {MatButtonModule} from "@angular/material/button";
import {MatListModule} from "@angular/material/list";
import { TextsComponent } from './texts/texts.component';
import { ItemComponent } from './texts/item/item.component';
import {MainService} from "./main.service";
import {HttpClientModule} from "@angular/common/http";
import { ModelsComponent } from './models/models.component';
import { UserInputComponent } from './user-input/user-input.component';
import {MatProgressSpinnerModule} from "@angular/material/progress-spinner";

@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    TextinputComponent,
    TagsinputComponent,
    ConfigureComponent,
    TextsComponent,
    ItemComponent,
    ModelsComponent,
    UserInputComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    HttpClientModule,
    MatCardModule,
    MatFormFieldModule,
    MatIconModule,
    MatInputModule,
    FormsModule,
    MatButtonModule,
    MatListModule,
    MatProgressSpinnerModule
  ],
  providers: [MainService],
  bootstrap: [AppComponent]
})
export class AppModule { }
