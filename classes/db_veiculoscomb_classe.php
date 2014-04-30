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

//MODULO: veiculos
//CLASSE DA ENTIDADE veiculoscomb
class cl_veiculoscomb { 
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
   var $ve06_sequencial = 0; 
   var $ve06_veiccadcomb = 0; 
   var $ve06_veiculos = 0; 
   var $ve06_padrao = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ve06_sequencial = int4 = Cód. Sequencial 
                 ve06_veiccadcomb = int4 = Combústivel 
                 ve06_veiculos = int4 = Veiculo 
                 ve06_padrao = bool = Padrão 
                 ";
   //funcao construtor da classe 
   function cl_veiculoscomb() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("veiculoscomb"); 
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
       $this->ve06_sequencial = ($this->ve06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ve06_sequencial"]:$this->ve06_sequencial);
       $this->ve06_veiccadcomb = ($this->ve06_veiccadcomb == ""?@$GLOBALS["HTTP_POST_VARS"]["ve06_veiccadcomb"]:$this->ve06_veiccadcomb);
       $this->ve06_veiculos = ($this->ve06_veiculos == ""?@$GLOBALS["HTTP_POST_VARS"]["ve06_veiculos"]:$this->ve06_veiculos);
       $this->ve06_padrao = ($this->ve06_padrao == "f"?@$GLOBALS["HTTP_POST_VARS"]["ve06_padrao"]:$this->ve06_padrao);
     }else{
       $this->ve06_sequencial = ($this->ve06_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ve06_sequencial"]:$this->ve06_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ve06_sequencial){ 
      $this->atualizacampos();
     if($this->ve06_veiccadcomb == null ){ 
       $this->erro_sql = " Campo Combústivel nao Informado.";
       $this->erro_campo = "ve06_veiccadcomb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve06_veiculos == null ){ 
       $this->erro_sql = " Campo Veiculo nao Informado.";
       $this->erro_campo = "ve06_veiculos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ve06_padrao == null ){ 
       $this->erro_sql = " Campo Padrão nao Informado.";
       $this->erro_campo = "ve06_padrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ve06_sequencial == "" || $ve06_sequencial == null ){
       $result = db_query("select nextval('veiculoscomb_ve06_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: veiculoscomb_ve06_sequencial_seq do campo: ve06_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ve06_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from veiculoscomb_ve06_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ve06_sequencial)){
         $this->erro_sql = " Campo ve06_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ve06_sequencial = $ve06_sequencial; 
       }
     }
     if(($this->ve06_sequencial == null) || ($this->ve06_sequencial == "") ){ 
       $this->erro_sql = " Campo ve06_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into veiculoscomb(
                                       ve06_sequencial 
                                      ,ve06_veiccadcomb 
                                      ,ve06_veiculos 
                                      ,ve06_padrao 
                       )
                values (
                                $this->ve06_sequencial 
                               ,$this->ve06_veiccadcomb 
                               ,$this->ve06_veiculos 
                               ,'$this->ve06_padrao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Combustivel de veiculos ($this->ve06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Combustivel de veiculos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Combustivel de veiculos ($this->ve06_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve06_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ve06_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10981,'$this->ve06_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1894,10981,'','".AddSlashes(pg_result($resaco,0,'ve06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1894,10982,'','".AddSlashes(pg_result($resaco,0,'ve06_veiccadcomb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1894,10983,'','".AddSlashes(pg_result($resaco,0,'ve06_veiculos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1894,10984,'','".AddSlashes(pg_result($resaco,0,'ve06_padrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ve06_sequencial=null,$dbwhere=null) { 
      $this->atualizacampos();
     $sql = " update veiculoscomb set ";
     $virgula = "";
     if(trim($this->ve06_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve06_sequencial"])){ 
       $sql  .= $virgula." ve06_sequencial = $this->ve06_sequencial ";
       $virgula = ",";
       if(trim($this->ve06_sequencial) == null ){ 
         $this->erro_sql = " Campo Cód. Sequencial nao Informado.";
         $this->erro_campo = "ve06_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve06_veiccadcomb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve06_veiccadcomb"])){ 
       $sql  .= $virgula." ve06_veiccadcomb = $this->ve06_veiccadcomb ";
       $virgula = ",";
       if(trim($this->ve06_veiccadcomb) == null ){ 
         $this->erro_sql = " Campo Combústivel nao Informado.";
         $this->erro_campo = "ve06_veiccadcomb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve06_veiculos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve06_veiculos"])){ 
       $sql  .= $virgula." ve06_veiculos = $this->ve06_veiculos ";
       $virgula = ",";
       if(trim($this->ve06_veiculos) == null ){ 
         $this->erro_sql = " Campo Veiculo nao Informado.";
         $this->erro_campo = "ve06_veiculos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ve06_padrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ve06_padrao"])){ 
       $sql  .= $virgula." ve06_padrao = '$this->ve06_padrao' ";
       $virgula = ",";
       if(trim($this->ve06_padrao) == null ){ 
         $this->erro_sql = " Campo Padrão nao Informado.";
         $this->erro_campo = "ve06_padrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ve06_sequencial!=null){
       $sql .= " ve06_sequencial = $this->ve06_sequencial";
     }
     if($dbwhere!=null){
       $sql .= $dbwhere;
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ve06_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10981,'$this->ve06_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve06_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1894,10981,'".AddSlashes(pg_result($resaco,$conresaco,'ve06_sequencial'))."','$this->ve06_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve06_veiccadcomb"]))
           $resac = db_query("insert into db_acount values($acount,1894,10982,'".AddSlashes(pg_result($resaco,$conresaco,'ve06_veiccadcomb'))."','$this->ve06_veiccadcomb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve06_veiculos"]))
           $resac = db_query("insert into db_acount values($acount,1894,10983,'".AddSlashes(pg_result($resaco,$conresaco,'ve06_veiculos'))."','$this->ve06_veiculos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ve06_padrao"]))
           $resac = db_query("insert into db_acount values($acount,1894,10984,'".AddSlashes(pg_result($resaco,$conresaco,'ve06_padrao'))."','$this->ve06_padrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Combustivel de veiculos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Combustivel de veiculos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ve06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ve06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ve06_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ve06_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10981,'$ve06_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1894,10981,'','".AddSlashes(pg_result($resaco,$iresaco,'ve06_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1894,10982,'','".AddSlashes(pg_result($resaco,$iresaco,'ve06_veiccadcomb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1894,10983,'','".AddSlashes(pg_result($resaco,$iresaco,'ve06_veiculos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1894,10984,'','".AddSlashes(pg_result($resaco,$iresaco,'ve06_padrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from veiculoscomb
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ve06_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ve06_sequencial = $ve06_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Combustivel de veiculos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ve06_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Combustivel de veiculos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ve06_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ve06_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:veiculoscomb";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ve06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from veiculoscomb ";
     $sql .= "      inner join veiccadcomb    on veiccadcomb.ve26_codigo = veiculoscomb.ve06_veiccadcomb";
     $sql .= "      inner join veiculos       on veiculos.ve01_codigo = veiculoscomb.ve06_veiculos";
     $sql .= "      inner join veiccentral    on veiccentral.ve40_veiculos      = veiculos.ve01_codigo";
     $sql .= "      inner join veiccadcentral on veiccadcentral.ve36_sequencial = veiccentral.ve40_veiccadcentral";
     $sql .= "      inner join db_depart      on db_depart.coddepto             = veiccadcentral.ve36_coddepto";
     $sql .= "      inner join ceplocalidades on ceplocalidades.cp05_codlocalidades = veiculos.ve01_ceplocalidades";
     $sql .= "      inner join veiccadtipo    on veiccadtipo.ve20_codigo = veiculos.ve01_veiccadtipo";
     $sql .= "      left  join veiccadmarca   on veiccadmarca.ve21_codigo = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo  on veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo";
     $sql .= "      left  join veiccadcor     on veiccadcor.ve23_codigo = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiccadtipocapacidade on veiccadtipocapacidade.ve24_codigo = veiculos.ve01_veiccadtipocapacidade";
     $sql .= "      inner join veiccadcategcnh       on veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join veiccadproced         on  veiccadproced.ve25_codigo = veiculos.ve01_veiccadproced";
     $sql .= "      inner join veiccadpotencia       on  veiccadpotencia.ve31_codigo = veiculos.ve01_veiccadpotencia";
     $sql .= "      inner join veiccadcateg as a     on a.ve32_codigo = veiculos.ve01_veiccadcateg";
     $sql2 = "";
     if($dbwhere==""){
       if($ve06_sequencial!=null ){
         $sql2 .= " where veiculoscomb.ve06_sequencial = $ve06_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_comb ( $ve06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from veiculoscomb ";
     $sql .= "      inner join veiccadcomb    on veiccadcomb.ve26_codigo = veiculoscomb.ve06_veiccadcomb";
     $sql .= "      inner join veiculos       on veiculos.ve01_codigo = veiculoscomb.ve06_veiculos";
     $sql .= "      left join veiccentral    on veiccentral.ve40_veiculos      = veiculos.ve01_codigo";
     $sql .= "      left join veiccadcentral on veiccadcentral.ve36_sequencial = veiccentral.ve40_veiccadcentral";
     $sql .= "      left join db_depart      on db_depart.coddepto             = veiccadcentral.ve36_coddepto";
     $sql .= "      inner join ceplocalidades on ceplocalidades.cp05_codlocalidades = veiculos.ve01_ceplocalidades";
     $sql .= "      inner join veiccadtipo    on veiccadtipo.ve20_codigo = veiculos.ve01_veiccadtipo";
     $sql .= "      inner  join veiccadmarca   on veiccadmarca.ve21_codigo = veiculos.ve01_veiccadmarca";
     $sql .= "      inner join veiccadmodelo  on veiccadmodelo.ve22_codigo = veiculos.ve01_veiccadmodelo";
     $sql .= "      inner join veiccadcor     on veiccadcor.ve23_codigo = veiculos.ve01_veiccadcor";
     $sql .= "      inner join veiccadtipocapacidade on veiccadtipocapacidade.ve24_codigo = veiculos.ve01_veiccadtipocapacidade";
     $sql .= "      inner join veiccadcategcnh       on veiccadcategcnh.ve30_codigo = veiculos.ve01_veiccadcategcnh";
     $sql .= "      inner join veiccadproced         on  veiccadproced.ve25_codigo = veiculos.ve01_veiccadproced";
     $sql .= "      inner join veiccadpotencia       on  veiccadpotencia.ve31_codigo = veiculos.ve01_veiccadpotencia";
     $sql .= "      inner join veiccadcateg as a     on a.ve32_codigo = veiculos.ve01_veiccadcateg";
     $sql2 = "";
     if($dbwhere==""){
       if($ve06_sequencial!=null ){
         $sql2 .= " where veiculoscomb.ve06_sequencial = $ve06_sequencial ";
       }
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
}
   function sql_query_file ( $ve06_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from veiculoscomb ";
     $sql2 = "";
     if($dbwhere==""){
       if($ve06_sequencial!=null ){
         $sql2 .= " where veiculoscomb.ve06_sequencial = $ve06_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>