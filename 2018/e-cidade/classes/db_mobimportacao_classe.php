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

//MODULO: cadastro
//CLASSE DA ENTIDADE mobimportacao
class cl_mobimportacao { 
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
   var $j95_codimporta = 0; 
   var $j95_pda = 0; 
   var $j95_data_dia = null; 
   var $j95_data_mes = null; 
   var $j95_data_ano = null; 
   var $j95_data = null; 
   var $j95_idusuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j95_codimporta = int4 = Código Importação 
                 j95_pda = int4 = Pda 
                 j95_data = date = Data 
                 j95_idusuario = int4 = Cod. Usuário 
                 ";
   //funcao construtor da classe 
   function cl_mobimportacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mobimportacao"); 
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
       $this->j95_codimporta = ($this->j95_codimporta == ""?@$GLOBALS["HTTP_POST_VARS"]["j95_codimporta"]:$this->j95_codimporta);
       $this->j95_pda = ($this->j95_pda == ""?@$GLOBALS["HTTP_POST_VARS"]["j95_pda"]:$this->j95_pda);
       if($this->j95_data == ""){
         $this->j95_data_dia = ($this->j95_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["j95_data_dia"]:$this->j95_data_dia);
         $this->j95_data_mes = ($this->j95_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["j95_data_mes"]:$this->j95_data_mes);
         $this->j95_data_ano = ($this->j95_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["j95_data_ano"]:$this->j95_data_ano);
         if($this->j95_data_dia != ""){
            $this->j95_data = $this->j95_data_ano."-".$this->j95_data_mes."-".$this->j95_data_dia;
         }
       }
       $this->j95_idusuario = ($this->j95_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["j95_idusuario"]:$this->j95_idusuario);
     }else{
       $this->j95_codimporta = ($this->j95_codimporta == ""?@$GLOBALS["HTTP_POST_VARS"]["j95_codimporta"]:$this->j95_codimporta);
     }
   }
   // funcao para inclusao
   function incluir ($j95_codimporta){ 
      $this->atualizacampos();
     if($this->j95_pda == null ){ 
       $this->erro_sql = " Campo Pda nao Informado.";
       $this->erro_campo = "j95_pda";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j95_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "j95_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j95_idusuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "j95_idusuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j95_codimporta == "" || $j95_codimporta == null ){
       $result = db_query("select nextval('mobimportacao_j95_codimporta_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mobimportacao_j95_codimporta_seq do campo: j95_codimporta"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j95_codimporta = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mobimportacao_j95_codimporta_seq");
       if(($result != false) && (pg_result($result,0,0) < $j95_codimporta)){
         $this->erro_sql = " Campo j95_codimporta maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j95_codimporta = $j95_codimporta; 
       }
     }
     if(($this->j95_codimporta == null) || ($this->j95_codimporta == "") ){ 
       $this->erro_sql = " Campo j95_codimporta nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mobimportacao(
                                       j95_codimporta 
                                      ,j95_pda 
                                      ,j95_data 
                                      ,j95_idusuario 
                       )
                values (
                                $this->j95_codimporta 
                               ,$this->j95_pda 
                               ,".($this->j95_data == "null" || $this->j95_data == ""?"null":"'".$this->j95_data."'")." 
                               ,$this->j95_idusuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Dados Importados ($this->j95_codimporta) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Dados Importados já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Dados Importados ($this->j95_codimporta) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j95_codimporta;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j95_codimporta));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9728,'$this->j95_codimporta','I')");
       $resac = db_query("insert into db_acount values($acount,1671,9728,'','".AddSlashes(pg_result($resaco,0,'j95_codimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1671,9731,'','".AddSlashes(pg_result($resaco,0,'j95_pda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1671,9730,'','".AddSlashes(pg_result($resaco,0,'j95_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1671,9729,'','".AddSlashes(pg_result($resaco,0,'j95_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j95_codimporta=null) { 
      $this->atualizacampos();
     $sql = " update mobimportacao set ";
     $virgula = "";
     if(trim($this->j95_codimporta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j95_codimporta"])){ 
       $sql  .= $virgula." j95_codimporta = $this->j95_codimporta ";
       $virgula = ",";
       if(trim($this->j95_codimporta) == null ){ 
         $this->erro_sql = " Campo Código Importação nao Informado.";
         $this->erro_campo = "j95_codimporta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j95_pda)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j95_pda"])){ 
       $sql  .= $virgula." j95_pda = $this->j95_pda ";
       $virgula = ",";
       if(trim($this->j95_pda) == null ){ 
         $this->erro_sql = " Campo Pda nao Informado.";
         $this->erro_campo = "j95_pda";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j95_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j95_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["j95_data_dia"] !="") ){ 
       $sql  .= $virgula." j95_data = '$this->j95_data' ";
       $virgula = ",";
       if(trim($this->j95_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "j95_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["j95_data_dia"])){ 
         $sql  .= $virgula." j95_data = null ";
         $virgula = ",";
         if(trim($this->j95_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "j95_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->j95_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j95_idusuario"])){ 
       $sql  .= $virgula." j95_idusuario = $this->j95_idusuario ";
       $virgula = ",";
       if(trim($this->j95_idusuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "j95_idusuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j95_codimporta!=null){
       $sql .= " j95_codimporta = $this->j95_codimporta";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j95_codimporta));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9728,'$this->j95_codimporta','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j95_codimporta"]))
           $resac = db_query("insert into db_acount values($acount,1671,9728,'".AddSlashes(pg_result($resaco,$conresaco,'j95_codimporta'))."','$this->j95_codimporta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j95_pda"]))
           $resac = db_query("insert into db_acount values($acount,1671,9731,'".AddSlashes(pg_result($resaco,$conresaco,'j95_pda'))."','$this->j95_pda',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j95_data"]))
           $resac = db_query("insert into db_acount values($acount,1671,9730,'".AddSlashes(pg_result($resaco,$conresaco,'j95_data'))."','$this->j95_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j95_idusuario"]))
           $resac = db_query("insert into db_acount values($acount,1671,9729,'".AddSlashes(pg_result($resaco,$conresaco,'j95_idusuario'))."','$this->j95_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Importados nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j95_codimporta;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados Importados nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j95_codimporta;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j95_codimporta;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j95_codimporta=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j95_codimporta));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9728,'$j95_codimporta','E')");
         $resac = db_query("insert into db_acount values($acount,1671,9728,'','".AddSlashes(pg_result($resaco,$iresaco,'j95_codimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1671,9731,'','".AddSlashes(pg_result($resaco,$iresaco,'j95_pda'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1671,9730,'','".AddSlashes(pg_result($resaco,$iresaco,'j95_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1671,9729,'','".AddSlashes(pg_result($resaco,$iresaco,'j95_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mobimportacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j95_codimporta != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j95_codimporta = $j95_codimporta ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Dados Importados nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j95_codimporta;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Dados Importados nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j95_codimporta;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j95_codimporta;
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
        $this->erro_sql   = "Record Vazio na Tabela:mobimportacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j95_codimporta=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mobimportacao ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = mobimportacao.j95_idusuario";
     $sql2 = "";
     if($dbwhere==""){
       if($j95_codimporta!=null ){
         $sql2 .= " where mobimportacao.j95_codimporta = $j95_codimporta "; 
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
   function sql_query_file ( $j95_codimporta=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mobimportacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($j95_codimporta!=null ){
         $sql2 .= " where mobimportacao.j95_codimporta = $j95_codimporta "; 
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