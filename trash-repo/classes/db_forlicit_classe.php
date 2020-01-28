<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: licitação
//CLASSE DA ENTIDADE forlicit
class cl_forlicit { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $l09_tipo = null; 
   var $l09_numero = null; 
   var $l09_numcgm = 0; 
   var $l09_data_dia = null; 
   var $l09_data_mes = null; 
   var $l09_data_ano = null; 
   var $l09_data = null; 
   var $l09_pessoa = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l09_tipo = char(     1) = Tipo de Licitacao 
                 l09_numero = char(     8) = Numero da Licitacao 
                 l09_numcgm = int4 = Numcgm do Fornecedor 
                 l09_data = date = Data retirada edital 
                 l09_pessoa = char(    40) = Pessoa que retirou edital 
                 ";
   //funcao construtor da classe 
   function cl_forlicit() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("forlicit"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->l09_tipo = ($this->l09_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["l09_tipo"]:$this->l09_tipo);
       $this->l09_numero = ($this->l09_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["l09_numero"]:$this->l09_numero);
       $this->l09_numcgm = ($this->l09_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["l09_numcgm"]:$this->l09_numcgm);
       if($this->l09_data == ""){
         $this->l09_data_dia = ($this->l09_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l09_data_dia"]:$this->l09_data_dia);
         $this->l09_data_mes = ($this->l09_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l09_data_mes"]:$this->l09_data_mes);
         $this->l09_data_ano = ($this->l09_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l09_data_ano"]:$this->l09_data_ano);
         if($this->l09_data_dia != ""){
            $this->l09_data = $this->l09_data_ano."-".$this->l09_data_mes."-".$this->l09_data_dia;
         }
       }
       $this->l09_pessoa = ($this->l09_pessoa == ""?@$GLOBALS["HTTP_POST_VARS"]["l09_pessoa"]:$this->l09_pessoa);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->l09_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Licitacao nao Informado.";
       $this->erro_campo = "l09_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l09_numero == null ){ 
       $this->erro_sql = " Campo Numero da Licitacao nao Informado.";
       $this->erro_campo = "l09_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l09_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm do Fornecedor nao Informado.";
       $this->erro_campo = "l09_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l09_data == null ){ 
       $this->erro_sql = " Campo Data retirada edital nao Informado.";
       $this->erro_campo = "l09_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l09_pessoa == null ){ 
       $this->erro_sql = " Campo Pessoa que retirou edital nao Informado.";
       $this->erro_campo = "l09_pessoa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into forlicit(
                                       l09_tipo 
                                      ,l09_numero 
                                      ,l09_numcgm 
                                      ,l09_data 
                                      ,l09_pessoa 
                       )
                values (
                                '$this->l09_tipo' 
                               ,'$this->l09_numero' 
                               ,$this->l09_numcgm 
                               ,".($this->l09_data == "null" || $this->l09_data == ""?"null":"'".$this->l09_data."'")." 
                               ,'$this->l09_pessoa' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fornecedores participantes da licitacao            () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fornecedores participantes da licitacao            já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fornecedores participantes da licitacao            () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update forlicit set ";
     $virgula = "";
     if(trim($this->l09_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l09_tipo"])){ 
       $sql  .= $virgula." l09_tipo = '$this->l09_tipo' ";
       $virgula = ",";
       if(trim($this->l09_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Licitacao nao Informado.";
         $this->erro_campo = "l09_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l09_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l09_numero"])){ 
       $sql  .= $virgula." l09_numero = '$this->l09_numero' ";
       $virgula = ",";
       if(trim($this->l09_numero) == null ){ 
         $this->erro_sql = " Campo Numero da Licitacao nao Informado.";
         $this->erro_campo = "l09_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l09_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l09_numcgm"])){ 
       $sql  .= $virgula." l09_numcgm = $this->l09_numcgm ";
       $virgula = ",";
       if(trim($this->l09_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm do Fornecedor nao Informado.";
         $this->erro_campo = "l09_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l09_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l09_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l09_data_dia"] !="") ){ 
       $sql  .= $virgula." l09_data = '$this->l09_data' ";
       $virgula = ",";
       if(trim($this->l09_data) == null ){ 
         $this->erro_sql = " Campo Data retirada edital nao Informado.";
         $this->erro_campo = "l09_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["l09_data_dia"])){ 
         $sql  .= $virgula." l09_data = null ";
         $virgula = ",";
         if(trim($this->l09_data) == null ){ 
           $this->erro_sql = " Campo Data retirada edital nao Informado.";
           $this->erro_campo = "l09_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->l09_pessoa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l09_pessoa"])){ 
       $sql  .= $virgula." l09_pessoa = '$this->l09_pessoa' ";
       $virgula = ",";
       if(trim($this->l09_pessoa) == null ){ 
         $this->erro_sql = " Campo Pessoa que retirou edital nao Informado.";
         $this->erro_campo = "l09_pessoa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fornecedores participantes da licitacao            nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fornecedores participantes da licitacao            nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from forlicit
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fornecedores participantes da licitacao            nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fornecedores participantes da licitacao            nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:forlicit";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>