const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Product = sequelize.define("product", {
      product_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      product_name: {
        type: Sequelize.STRING,
        unique:true,
        allowNull: false
      },
      product_description: {
        type: Sequelize.STRING,
        allowNull: false
      },
      merchant_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // Product belongsTo Merchant 1:1
          model: 'merchants',
          key: 'merchant_id'
        }  
      }
    });
    return Product;
  };