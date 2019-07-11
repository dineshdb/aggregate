const Sitemapper = require('sitemapper')
const dotenv = require('dotenv').config()

const { Model } = require('objection');
const { Source, Page} = require("./schema")

var knex = require('knex')({
  client: 'mysql2',
  connection: {
    host : 'database',
    user : 'aggregator',
    password : 'aggregator',
    database : 'db'
  }
});

Model.knex(knex);

const sleep = (milliseconds) => {
  return new Promise(resolve => setTimeout(resolve, milliseconds))
}

let sitemapper = new Sitemapper();
sitemapper.timeout = 5000;

async function fetch(url) {
	try {
		return await sitemapper.fetch(url)
	} catch (ex) {
		console.log("Could not fetch" + url)
	}
}

let base_urls = [
	'https://dbhattarai.info.np/sitemap.xml',
];

(async function(){
	try {
		// Sleep for 10 seconds so that all other services are ready.
		await sleep(10000)
	} catch(ex){
	}
	
	for (let url of base_urls) {
		let urls = await fetch(url)	
		add_individual_pages(urls.sites, url)
		// TODO: Seee which urls are new and make a list of new urls.
	}
})()


async function add_individual_pages(pages, site) {
	for (let page of pages) {
		await Page.query().insert({ url : page, })
		// Fetch page
		// Check if the page type is html
		// Extract title of the page.
		// Save page to database.
	
	}
}
