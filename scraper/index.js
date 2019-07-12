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

(async function(){
	// Sleep for 10 seconds to give database time to start
	await sleep(10000)
	let pages;
	try {
		pages = await Page.query().where('type', 'SITEMAP');
	} catch (ex){
		console.log("Error getting a list of pages, exiting...")
		process.exit(1);
	}
	
	for (let page of pages) {
		let urls = await fetch(page.url)
		await add_individual_pages(urls, page.url)
		// TODO: Seee which urls are new and make a list of new urls.
	}
})()


async function add_individual_pages(pages, site) {
	console.log(pages)
	for (let webpage of pages.sites) {
		await WebPage.query().insert({ url : webpage, })
		// Fetch page
		// Check if the page type is html
		// Extract title of the page.
		// Save page to database.
	
	}
}
