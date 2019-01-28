function TreeView(id, data, container, formEl){
    this.id = id;
    this.data = data;
    this.container = container;
    this.formEl = formEl;
    
    this.nodeData = {
        text: 'input',
        href: 'input',
        css: 'input',
        style: 'input',
        link: 'input',
        icon: 'input',
        description: 'input',
        displayColumn: 'checkbox'
    };
    
    this.onNodeSelected = function(event, node){
        this.data = this.clearSelected('', this.data);
        this.updateFormFromNode(node);
        var dataNode = this.getNode(node.location);
        dataNode.state = node.state;
    };
    
    this.updateNodeState = function(event, node) {
        var dataNode = this.getNode(node.location);
        dataNode.state = node.state;
    };
    
    this.render = function(){
        var self = this;
        $(id).treeview({
            data: self.data,
            onNodeSelected: self.onNodeSelected.bind(self),
            onNodeCollapsed: self.updateNodeState.bind(self),
            onNodeExpanded: self.updateNodeState.bind(self)
        });
        var treeData = btoa(JSON.stringify(self.data));
        this.formEl.val(treeData);
    };
    
    this.getNode = function(location) {
        var map = location.split('.');
        var node = this.data[map[0]];
        for(var  i =1; i< map.length; i++ ) {
            node = node.nodes[map[i]];
        }
        
        return node;
    };
    
    this.updateNode = function(location, data){
        var node = this.getNode(location);
        var dataField = this.nodeData;
        for(var key in dataField) {
            if (dataField.hasOwnProperty(key)) {
                node[key] = data[dataField[key]];
            }
        }
    };
    
    this.removeNode = function(location){
        var map = location.split('.');
        if (map.length == 1){
            this.data.splice(location, 1);
        } else {
            var node = this.data[map[0]];
            for(var  i =1; i< map.length-1; i++ ) {
                node = node.nodes[map[i]];
            }
            if (node.nodes && node.nodes.length > 1){
                node.nodes.splice(map[map.length-1],1);
            } else {
                node.nodes = undefined;
            }
        }
    };

    this.addNode = function(parent, node){
        if (parent.nodes) {
            var location = parent.location + '.'+ parent.nodes.length;
            node.location = location;
            parent.nodes.push(node);
        }else {
                location = parent.location + '.0';
                node.location = location;
                parent.nodes = [];
                parent.nodes.push(node);
        } 
    };
    
    this.copyNodeInfo = function(node){
        var newNode = {};
        var dataField = this.nodeData;
        for(var key in dataField) {
            if (dataField.hasOwnProperty(key)) {
                newNode[key] = node[key];
            }
        } 
        
        newNode.state = node.state;
        
        return newNode;
    };
    
    this.reCaupateTree  = function(parentLocation, tree){
        var newTree = [];
        var self = this;
        for(var i =0 ;i < tree.length; i++) {
            var node = tree[i];
            var copyNode = self.copyNodeInfo(node);
            copyNode.location = parentLocation? parentLocation + '.'+i: i.toString();

            if(node.nodes){
                copyNode.nodes = self.reCaupateTree(copyNode.location, node.nodes);
            }

            newTree.push(copyNode);
        }
        return newTree;
    };

    this.clearSelected  = function(parentLocation, tree){
        var newTree = [];
        var self = this;
        for(var i =0 ;i < tree.length; i++) {
            var node = tree[i];
            var copyNode = self.copyNodeInfo(node);
            if (copyNode.state){
                copyNode.state.selected = false;
            }
            
            copyNode.location = parentLocation? parentLocation + '.'+i: i.toString();

            if(node.nodes){
                copyNode.nodes = self.clearSelected(copyNode.location, node.nodes);
            }

            newTree.push(copyNode);
        }
        return newTree;
    };
    
    this.updateFormFromNode = function(node){
        var self = this;
        var dataField = this.nodeData;
        for(var key in dataField) {
            if (dataField.hasOwnProperty(key)) {
                self.setElementValue(key, node[key], dataField[key]);
            }
        };
        
    };
    
    this.updateNodeFromForm = function(node){
        var self = this;
        var dataField = this.nodeData;
        for(var key in dataField) {
            if (dataField.hasOwnProperty(key)) {
                node[key] = self.getElementValue(key, dataField[key]);
            }
        };
        
        return node;
    }
    
    this.setElementValue = function(id, value, type) {
        if (type == 'input') {
            this.container.find("[name='"+id+"']").val(value);
        } else if (type == 'checkbox') {
            this.container.find("[name='"+id+"']").attr('checked', value);
        }
    };
    
    this.getElementValue = function(id, type){
        if (type == 'input') {
            return this.container.find("[name='"+id+"']").val();
        }
        if (type == 'checkbox') {
            return this.container.find("[name='"+id+"']").prop('checked');
        }
    };
    
    this.getSelected = function(){
        var selected = $(this.id).treeview('getSelected');
        if (selected ) return selected[0];
        return null;
    };
    
    this.addChidren = function(){
        var node = {};
        node = this.updateNodeFromForm(node);
        var selected = this.getSelected();
        if (selected) {
            var parent = this.getNode(selected.location);
            this.addNode(parent, node);
        } else {
            node.location = this.data.length.toString();
            this.data.push(node);
        }
        
        this.render(this.id, this.data, this.container);
    }.bind(this);;
    
    this.updateNodeContent = function() {
        var selected = this.getSelected();
        var node = this.getNode(selected.location);
        this.updateNodeFromForm(node);
        this.render(this.id, this.data, this.container);
    }.bind(this);;
    
    this.removeChildren = function() {
        var selected = this.getSelected();
        if (selected) {
            this.removeNode(selected.location);
            this.data = this.reCaupateTree('', this.data);
            this.render(this.id, this.data, this.container);
        }
    }.bind(this);
}