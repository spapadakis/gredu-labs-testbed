(function () {
    'use strict';

    var Staff,
        Employee,
        EmployeeView,
        StaffView,
        ModalView,
        employeeTemplate;

    Employee = Backbone.Model.extend({ idAttribute: 'id' });

    Staff = Backbone.Collection.extend({
        model: Employee,
        comparator: 'surname'
    });

    EmployeeView = Backbone.View.extend({
        tagName: 'tr',
        template: (function () {
            if (typeof employeeTemplate === 'undefined') {
                employeeTemplate = _.template($('#employee-row').html());
            }
            return employeeTemplate;
        }()),
        initialize: function () {
            this.model.on('change', this.render, this);
        },
        render: function () {
            this.$el.html(this.template({ employee: this.model.attributes }));
            this.$el.attr('data-id', this.model.get('id'));
            return this;
        }
    });

    StaffView = Backbone.View.extend({
        el: '#school',
        modal: null,
        events: {
            'click .btn-add-employee': 'addEmployee',
            'click tbody tr': 'editEmployee'
        },
        initialize: function () {
            var that = this;
            this.modal = new ModalView();
            _.each(this.$el.find('tbody tr'), function (tr) {
                var data = $(tr).data('employee'),
                    employee = new Employee(data);
                that.model.add(employee);
                new EmployeeView({ model: employee, el: tr });
                $(tr).attr('data-employee', null);
            });
            this.model.on('add', this.renderEmployee, this);
        },
        addEmployee: function (evt) {
            var that = this;
            evt.preventDefault();
            this.modal.render(function (data) {
                that.model.add(data);
                that.$el.find('.no-records').remove();
            });
            return this;
        },
        editEmployee: function (evt) {
            var employee = this.model.get($(evt.target).closest('tr').data('id'));
            if ($(evt.target).is('a')) return;
            if (!employee) return;
            this.modal.render(function (data) {
                employee.set(data);
            }, employee.attributes);
            return this;
        },
        renderEmployee: function (employee) {
            this.$el.find('tbody').append(new EmployeeView({
                model: employee
            }).render().el);
            return this;
        }
    });

    ModalView = Backbone.View.extend({
        el: '#employee-form-modal',
        form: null,
        events: {
            'submit form': 'submit',
        },
        initialize: function () {
            this.form = this.$el.find('form');
        },
        render: function (done, employee) {
            this.form.data('done', done);
            this.form[0].reset();
            this.form.find('input[type="hidden"]').val('');
            employee = employee || {};
            _.each(this.form[0].elements, function (element) {
                var element = $(element),
                    name = element.attr('name');
                element.val(employee[name] || '');
            });
            this.show();
        },
        show: function () {
            this.$el.modal('show');
        },
        hide: function () {
            this.$el.modal('hide');
        },
        submit: function (evt) {
            var data = _.reduce(this.form.serializeArray(), function (hash, pair) {
                hash[pair.name] = pair.value;
                return hash;
            }, {});
            var that = this;
            evt.preventDefault();
            $.post("", data).
                done(function(response){
                    that.form.data('done')(response); 
                    that.form.data('done', undefined);
                    that.hide();
                }).fail(function (xhr, err) {
                    alert(xhr.statusText);
                });
        }
    });

    new StaffView({ model: new Staff() });
}());
