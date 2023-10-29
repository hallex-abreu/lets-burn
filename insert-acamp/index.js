const fs = require("fs");
const mysql = require("mysql");
const XLSX = require("xlsx");

// Configuração do banco de dados
const dbConfig = {
  host: "108.167.132.72",
  user: "lippfy60_root",
  password: "ZXcv!@34",
  database: "lippfy60_letsburndb",
};

const connection = mysql.createConnection(dbConfig);

// Carregue o arquivo Excel
const workbook = XLSX.readFile("check-in.xlsx");
const worksheet = workbook.Sheets[workbook.SheetNames[0]];

// Função para inserir dados no banco de dados
function insertDataIntoDatabase(data) {
  const sql =
    "INSERT INTO customers (name, team_name, room_name, room_leader) VALUES (?, ?, ?, ?)";
  connection.query(
    sql,
    [
      data.name,
      data.team_name,
      data.room_name,
      data.room_leader,
    ],
    (error, results) => {
      if (error) {
        console.error("Erro ao inserir os dados:", error);
      } else {
        console.log("Dados inseridos com sucesso!");
      }
    }
  );
}

// Crie um objeto que mapeia os cabeçalhos das colunas às colunas da planilha
const columnMap = {
  name: "A",
  team_name: "C",
  room_name: "B",
  room_leader: "E",
};

// Obtenha o número de linhas na planilha
const range = XLSX.utils.decode_range(worksheet["!ref"]);
const numRows = range.e.r;

// Itere sobre as linhas do arquivo Excel e insira os dados no banco de dados
for (let row = 2; row <= numRows; row++) {
  // Comece em 2 para pular o cabeçalho
  const data = {};
  for (const column in columnMap) {
    const cellAddress = columnMap[column] + row;
    data[column] = worksheet[cellAddress] ? worksheet[cellAddress].v : null;
  }
  console.log(data)
  insertDataIntoDatabase(data);
}

// Encerre a conexão com o banco de dados após a conclusão
connection.end();
