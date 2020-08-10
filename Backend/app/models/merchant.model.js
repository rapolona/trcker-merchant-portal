const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Merchant = sequelize.define("merchant", {
      merchant_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      name: {
        type: Sequelize.STRING,
        unique:true,
        allowNull: false
      },
      address: {
        type: Sequelize.STRING
      },
      trade_name: {
        type: Sequelize.STRING
      },
      sector: {
        type: Sequelize.STRING
      },
      business_structure: {
        type: Sequelize.STRING
      },
      authorized_representative: {
        type: Sequelize.STRING
      },
      position: {
        type: Sequelize.STRING
      },
      contact_person: {
        type: Sequelize.STRING
      },
      contact_number: {
        type: Sequelize.STRING
      },
      email_address: {
        type: Sequelize.STRING
      },
      business_nature: {
        type: Sequelize.STRING
      },
      product_type: {
        type: Sequelize.STRING
      }
    });
    return Merchant;
  };