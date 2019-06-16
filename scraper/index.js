const Sitemapper = require('sitemapper')
const dotenv = require('dotenv').config()
//const Pocket = require('./src/pocket')
const open = require('open')
 
let sitemapper = new Sitemapper();
sitemapper.timeout = 5000;

async function fetch(url) {
	try {
		let data = await sitemapper.fetch(url)
		return data
	} catch (ex) {
		console.log("Could not fetch" + url)
	}
}

let base_urls = [
	'https://dbhattarai.info.np/sitemap.xml',
];

(async function(){
	let consumer_key = process.env.POCKET_CONSUMER_KEY
//	let url = await pocket.requestToken({consumer_key})
//	let o = await open(url, {wait: true})			
//	let pocket = new Pocket({consumer_key})
//	console.log(resp)
	for (let url of base_urls) {
		let urls = await fetch(url)	
		console.log(urls)
		// TODO: Save these urls to the database along with their last updated dates.
		// TODO: Seee which urls are new and make a list of new urls.
	}
})()
