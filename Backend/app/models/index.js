const dbConfig = require("../../config/db.config.js");

const Sequelize = require("sequelize");

const sequelize = new Sequelize(dbConfig.DB,dbConfig.USER, dbConfig.PASSWORD, {
    host: dbConfig.HOST,
    dialect: dbConfig.dialect,
    operatorAliases: false,

    pool:{
        max: dbConfig.max,
        min: dbConfig.min,
        acquire:dbConfig.acquire,
        idle: dbConfig.pool.idle
    }
});

const db = {}

db.Sequelize = Sequelize;
db.sequelize = sequelize;

db.admins = require("./admin.model.js")(sequelize, Sequelize);
db.admindetails = require("./admindetail.model.js")(sequelize, Sequelize);
db.adminsessions = require("./adminsession.model.js")(sequelize, Sequelize);

//Merchant Related Tables
db.merchants = require("./merchant.model.js")(sequelize, Sequelize);
db.branches = require("./branch.model.js")(sequelize, Sequelize);
db.products = require("./product.model.js")(sequelize, Sequelize);

db.admins.hasOne(db.admindetails, { foreignKey: "admin_id", as:"adminDetails"});
db.admins.hasOne(db.adminsessions, {foreignKey: "admin_id", as:"adminSessions"});
db.merchants.hasMany(db.admins, {foreignKey: "merchant_id", as:"merchantAdmins"})

//Merchant Related Associations
db.merchants.hasMany(db.branches, {foreignKey:'merchant_id'});
db.branches.belongsTo(db.merchants, {foreignKey: "merchant_id"});

db.merchants.hasMany(db.products, {foreignKey:'merchant_id'});
db.products.belongsTo(db.merchants, {foreignKey: "merchant_id"});

module.exports = db