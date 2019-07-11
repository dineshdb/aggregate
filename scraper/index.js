const Sitemapper = require('sitemapper')
const dotenv = require('dotenv').config()

const { Model } = require('objection');
const { WebPage, Page} = require("./schema")

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
	let pages = await Page.query();
	
	console.log(`Pages: ${pages}`)
	
	for (let url of pages) {
		let urls = await fetch(url)
		add_individual_pages(urls.sites, url)
		// TODO: Seee which urls are new and make a list of new urls.
	}
})()


async function add_individual_pages(pages, site) {
	for (let webpage of pages) {
		await WebPage.query().insert({ url : webpage, })
		// Fetch page
		// Check if the page type is html
		// Extract title of the page.
		// Save page to database.
	
	}
}
