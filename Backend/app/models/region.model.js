const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Region = sequelize.define("region", {
      Id:{
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true,
        unique:true
      },
      label: {
        type: Sequelize.STRING(64),
        unique:true,
        allowNull: false
      },
      regionCode: {
        type: Sequelize.STRING(64),
        unique:true,
        allowNull: false
      }
    });
    return Region;
  };