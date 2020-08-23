import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TagsinputComponent } from './tagsinput.component';

describe('TagsinputComponent', () => {
  let component: TagsinputComponent;
  let fixture: ComponentFixture<TagsinputComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TagsinputComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TagsinputComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
