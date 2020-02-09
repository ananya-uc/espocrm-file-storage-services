<div class="button-container">
    <button class="btn btn-primary" data-action="edit" data-name="edit" data-toggle="tooltip" title="Edit"><span
            class="short-label fas fa-pen-nib"></span></button>
    <div class="pull-right">
        <button class="btn btn-info" data-action="test" data-name="test" data-toggle="tooltip" title="Test Connection">Test Connection</button>
        <button class="btn btn-success" data-action="save" data-name="save" data-toggle="tooltip" title="Save"><span
                class="short-label fas fa-save"></span></button>
        <button class="btn btn-danger" data-action="cancel" data-name="cancel" data-toggle="tooltip" title="Close"><span
                class="short-label fas fa-times"></span></button>
        <button class="btn btn-danger" data-action="delete" data-name="delete" data-toggle="tooltip" title="Close"><span
                class="short-label fas fa-trash"></span></button>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"> <span class="short-label fas fa-street-view"></span> Overview </h4>
            </div>
            <div class="panel-body panel-body-form">
                <div class="cell form-group" data-name="enabled">
                    <label class="control-label"
                        data-name="enabled">{{translate 'enabled' scope='Integration' category='fields'}}</label>
                    <div class="field" data-name="enabled">{{{enabled}}}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="panel panel-danger" data-name="userSettings">
            <div class="panel-heading">
                <h4 class="panel-title"> <span class="short-label fas fa-user-cog"></span> User Settings </h4>
            </div>
            <div class="panel-body panel-body-form">
                {{#each dataFieldList}}
                <div class="cell form-group" data-name="{{./this}}">
                    <label class="control-label"
                        data-name="{{./this}}">{{translate this scope='Integration' category='fields'}}
                        <span class="required-sign"> *</span>
                    </label>
                    <div class="field" data-name="{{./this}}">{{{var this ../this}}}</div>
                </div>
                {{/each}}

            </div>
        </div>
    </div>
</div>

{{#if helpText}}
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-success"> {{helpText}} </div>
    </div>
</div>
{{/if}}