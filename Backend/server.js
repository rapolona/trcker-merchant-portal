const express = require("express");
const bodyParser = require("body-parser");
const cors = require("cors");

const app = express();

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended:true}));


const db = require("./app/models")
db.sequelize.sync()

var glob = require( 'glob' )
  , path = require( 'path' );

glob.sync( './app/routes/*.js' ).forEach( function( file ) {
  require( path.resolve( file ) )(app);
});


const PORT = process.env.PORT || 6001;

app.listen(PORT, ()=>{
    console.log(`Server is running on port ${PORT}`);
})



