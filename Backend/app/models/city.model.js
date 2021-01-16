const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const City = sequelize.define("city", {
      Id:{
        type: DataTypes.INTEGER,
        primaryKey: true,
        unique:true
      },
      Psgccode: {
        type: Sequelize.STRING(64),
        allowNull: false
      },
      label: {
        type: Sequelize.STRING(255),
        unique:true,
        allowNull: false
      },
      Regdesc: {
        type: Sequelize.STRING(64),
        allowNull: false
      },
      Provcode: {
        type: Sequelize.STRING(64),
        allowNull: false
      },
      value: {
        type: Sequelize.STRING(64),
        allowNull: false
      }
    });
    return City;
  };