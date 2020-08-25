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
        type: Sequelize.STRING(255)
      },
      trade_name: {
        type: Sequelize.STRING(64)
      },
      sector: {
        type: Sequelize.STRING(64)
      },
      business_structure: {
        type: Sequelize.STRING(64)
      },
      authorized_representative: {
        type: Sequelize.STRING(64)
      },
      position: {
        type: Sequelize.STRING(64)
      },
      contact_person: {
        type: Sequelize.STRING(64)
      },
      contact_number: {
        type: Sequelize.STRING(64),
        validate: {
          isNumeric: true
        }
      },
      email_address: {
        type: Sequelize.STRING(64),
        validate: {
          isEmail: true
        }
      },
      business_nature: {
        type: Sequelize.STRING(64)
      },
      product_type: {
        type: Sequelize.STRING(64)
      }
    });
    return Merchant;
  };