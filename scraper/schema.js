const { Model } = require('objection');

class Source extends Model {
	static get tableName(){
		return 'sources';
	}
	
	static get idColumn(){
		return 'id';
	}
	
	static get jsonSchema(){
		return {
			type: 'object',
			required: [ ],
			properties: {
				id : { type: 'integer' },
				url: { type: 'string'},
				title: {type: 'string'},			
			}		
		}	
	}
}

class Page extends Model {
	static get tableName(){
		return 'pages';
	}
	
	static get idColumn(){
		return 'id';
	}
	
	static get jsonSchema(){
		return {
			type: 'object',
			required: [ 'url'],
			properties: {
				id : { type: 'integer' },
				url: { type: 'string'},
				title: { type: 'string'},
				source_id: {type: 'integer'},
				last_updated: { type: 'string'},
			}	
		}	
	}
}

module.exports = {
	Source,
	Page,
}
