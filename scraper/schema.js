const { Model } = require('objection');

class WebPage extends Model {
	static get tableName(){
		return 'webpages';
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
				sourceId: { type: 'integer'},			
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
				pageId : { type: 'integer' },
				url: { type: 'string'},
				title: { type: 'string'},
				lastUpdated: { type: 'date'},
			}	
		}	
	}
}

module.exports = {
	WebPage,
	Page,
}
