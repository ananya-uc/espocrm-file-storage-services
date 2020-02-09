Espo.define('file-storage:views/admin/integrations/aws-console/edit', ['views/admin/integrations/edit', 'model',], function (Dep, Model) {

    return Dep.extend({

        template: 'file-storage:admin/integrations/aws-console/edit',

        mode: 'detail',

        forceRenewed: false,

        // Event listeners
        onEditEvent: function () {
            this.addActionHandler('edit', function () {
                this.showButton('save')
                $(this.options.el).find('input[data-name=enabled]').removeAttr('disabled')
                for (fieldName in this.fields) {
                    if (this.fields[fieldName].readOnly != true) {
                        view = this.getView(fieldName)
                        view.setMode('edit')
                        view.render()
                        this.mode = 'edit'
                    }
                }
            }.bind(this))
        },

        preSetup: function () {
            this.panels = []
            this.backupFieldList = []
            this.integration = this.options.integration

            this.helpText = false
            if (this.getLanguage().has(this.integration, 'help', 'Integration')) {
                this.helpText = this.translate(this.integration, 'help', 'Integration')
            }

            this.fieldList = []
            this.dataFieldList = []
            this.mode = 'detail'
        },

        setup: function () {
            this.preSetup()

            this.model = new Model()
            this.model.id = this.integration
            this.model.name = 'Integration'
            this.model.urlRoot = 'Integration'
            this.model.defs = {
                fields: {
                    enabled: {
                        required: true,
                        type: 'bool',
                    },
                },
            }
            this.wait(true)

            this.fields = this.getMetadata().get('integrations.' + this.integration + '.fields')
            Object.keys(this.fields).forEach(function (name) {
                this.model.defs.fields[name] = this.fields[name]
                this.dataFieldList.push(name)
            }, this)

            this.model.populateDefaults()

            this.listenToOnce(this.model, 'sync', function () {
                this.createFieldView('bool', 'enabled')
                Object.keys(this.fields).forEach(function (name) {
                    this.fields[name]['inlineEditDisabled'] = true
                    this.createView(name, this.getFieldManager().getViewName(this.fields[name].type), {
                        model: this.model,
                        el: this.options.el + ' .field[data-name="' + name + '"]',
                        defs: {
                            name: name,
                            params: this.fields[name],
                        },
                        mode: 'detail',
                        readOnly: this.fields[name].readOnly,
                    })
                    this.fieldList.push(name)
                }, this)

                this.wait(false)
            }, this)

            this.model.fetch()

            this.panels.push('userSettings')

            // Event handlers
            this.onEditEvent()
            this.addActionHandler('test', () => {
                this.testConnection()
            })
            this.addActionHandler('delete', () => {
                this.delete()
            })
        },

        hideButton: function (name) {
            this.$el.find('button[data-name=' + name + ']').addClass('hide')
        },

        showButton: function (name) {
            this.$el.find('button[data-name=' + name + ']').removeClass('hide')
        },

        hideField: function (name) {
            this.$el.find('div.form-group[data-name=' + name + ']').addClass('hide')
        },

        showField: function (name) {
            this.$el.find('div.form-group[data-name=' + name + ']').removeClass('hide')
        },

        hidePanel: function (name) {
            this.$el.find('div.panel[data-name=' + name + ']').addClass('hide')
        },

        showPanel: function (name) {
            this.$el.find('div.panel[data-name=' + name + ']').removeClass('hide')
        },

        afterRender: function () {
            if (!this.model.get('enabled')) {
                this.backupFieldList = this.fieldList.slice()
                this.fieldList = []
                this.panels.forEach(function (name) {
                    this.hidePanel(name)
                }, this)
            }

            this.enabledListener()
            this.hideButton('save')
            this.$el.find('input[data-name=enabled]').attr('disabled', 'disabled')
        },

        // Listeners
        enabledListener: function () {
            this.listenTo(this.model, 'change:enabled', function () {
                if (this.model.get('enabled')) {
                    this.fieldList = this.backupFieldList.slice()
                    this.panels.forEach(function (name) {
                        this.showPanel(name)
                    }, this)

                    if (this.model.get('adobeAccessToken')) {
                        this.showPanel('fieldMapping')
                    }
                } else {
                    this.backupFieldList = this.fieldList.slice()
                    this.fieldList = []
                    this.panels.forEach(function (name) {
                        this.hidePanel(name)
                    }, this)
                    this.hidePanel('fieldMapping')
                }
            }, this)
        },

        checkRequiredFieldNotSet: function () {
            var notValid = false
            this.fieldList.forEach(function (field) {
                var fieldView = this.getView(field)
                if (fieldView && !fieldView.disabled) {
                    if (!this.model.has(field)) {
                        this.model.set(field, null)
                    }
                    notValid = fieldView.validate() || notValid
                }
            }, this)
            return notValid
        },

        testConnection: function () {
            if (!this.model.get('enabled')) {
                this.notify('Integration not enabled', 'error')
                return;
            }

            this.ajaxGetRequest('FileStorage/action/TestConnection').done((result) => {
                if (!result) {
                    this.notify('Settings Not Found or Incorrect', 'error')
                    return;
                }
                this.notify('Connected', 'success')
            })
        },

        save: function () {
            if (!this.model.get('enabled')) {
                this.model.set('enabled', false)
                Dep.prototype.save.call(this)
                this.hideButton('save')
                this.$el.find('input[data-name=enabled]').attr('disabled', 'disabled')
                return
            }

            if (this.checkRequiredFieldNotSet()) {
                this.notify('Required fields not found', 'error')
                return
            }

            Dep.prototype.save.call(this)

            this.hideButton('save')
            this.$el.find('input[data-name=enabled]').attr('disabled', 'disabled')

            for (fieldName in this.fields) {
                if (this.fields[fieldName].readOnly != true) {
                    view = this.getView(fieldName)
                    view.setMode('detail')
                    view.render()
                }
            }
        },

        delete: function () {
            this.model.save({'enabled': false}, {success: () => {
                this.model.destroy({success: (model, response) => {
                    window.location.reload()
                }})
            }})
        }
    })

})