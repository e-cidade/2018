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
//CLASSE DA ENTIDADE averbacgm
class cl_averbacgm { 
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
   var $j76_codigo = 0; 
   var $j76_averbacao = 0; 
   var $j76_numcgm = 0; 
   var $j76_tipo = 0; 
   var $j76_principal = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j76_codigo = int4 = C�digo 
                 j76_averbacao = int4 = C�digo Averba��o 
                 j76_numcgm = int4 = Numcgm 
                 j76_tipo = int4 = Tipo 
                 j76_principal = bool = Principal 
                 ";
   //funcao construtor da classe 
   function cl_averbacgm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("averbacgm"); 
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
       $this->j76_codigo = ($this->j76_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j76_codigo"]:$this->j76_codigo);
       $this->j76_averbacao = ($this->j76_averbacao == ""?@$GLOBALS["HTTP_POST_VARS"]["j76_averbacao"]:$this->j76_averbacao);
       $this->j76_numcgm = ($this->j76_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["j76_numcgm"]:$this->j76_numcgm);
       $this->j76_tipo = ($this->j76_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["j76_tipo"]:$this->j76_tipo);
       $this->j76_principal = ($this->j76_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["j76_principal"]:$this->j76_principal);
     }else{
       $this->j76_codigo = ($this->j76_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j76_codigo"]:$this->j76_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($j76_codigo){ 
      $this->atualizacampos();
     if($this->j76_averbacao == null ){ 
       $this->erro_sql = " Campo C�digo Averba��o nao Informado.";
       $this->erro_campo = "j76_averbacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j76_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "j76_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j76_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "j76_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j76_principal == null ){ 
       $this->erro_sql = " Campo Principal nao Informado.";
       $this->erro_campo = "j76_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j76_codigo == "" || $j76_codigo == null ){
       $result = db_query("select nextval('averbacgm_j76_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: averbacgm_j76_codigo_seq do campo: j76_codigo"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j76_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from averbacgm_j76_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $j76_codigo)){
         $this->erro_sql = " Campo j76_codigo maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j76_codigo = $j76_codigo; 
       }
     }
     if(($this->j76_codigo == null) || ($this->j76_codigo == "") ){ 
       $this->erro_sql = " Campo j76_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into averbacgm(
                                       j76_codigo 
                                      ,j76_averbacao 
                                      ,j76_numcgm 
                                      ,j76_tipo 
                                      ,j76_principal 
                       )
                values (
                                $this->j76_codigo 
                               ,$this->j76_averbacao 
                               ,$this->j76_numcgm 
                               ,$this->j76_tipo 
                               ,'$this->j76_principal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "novos promitentes ou proprietarios ($this->j76_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "novos promitentes ou proprietarios j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "novos promitentes ou proprietarios ($this->j76_codigo) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j76_codigo;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j76_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9600,'$this->j76_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1651,9600,'','".AddSlashes(pg_result($resaco,0,'j76_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1651,9601,'','".AddSlashes(pg_result($resaco,0,'j76_averbacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1651,9602,'','".AddSlashes(pg_result($resaco,0,'j76_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1651,9603,'','".AddSlashes(pg_result($resaco,0,'j76_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1651,9604,'','".AddSlashes(pg_result($resaco,0,'j76_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j76_codigo=null) { 
      $this->atualizacampos();
     $sql = " update averbacgm set ";
     $virgula = "";
     if(trim($this->j76_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j76_codigo"])){ 
       $sql  .= $virgula." j76_codigo = $this->j76_codigo ";
       $virgula = ",";
       if(trim($this->j76_codigo) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "j76_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j76_averbacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j76_averbacao"])){ 
       $sql  .= $virgula." j76_averbacao = $this->j76_averbacao ";
       $virgula = ",";
       if(trim($this->j76_averbacao) == null ){ 
         $this->erro_sql = " Campo C�digo Averba��o nao Informado.";
         $this->erro_campo = "j76_averbacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j76_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j76_numcgm"])){ 
       $sql  .= $virgula." j76_numcgm = $this->j76_numcgm ";
       $virgula = ",";
       if(trim($this->j76_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "j76_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j76_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j76_tipo"])){ 
       $sql  .= $virgula." j76_tipo = $this->j76_tipo ";
       $virgula = ",";
       if(trim($this->j76_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "j76_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j76_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j76_principal"])){ 
       $sql  .= $virgula." j76_principal = '$this->j76_principal' ";
       $virgula = ",";
       if(trim($this->j76_principal) == null ){ 
         $this->erro_sql = " Campo Principal nao Informado.";
         $this->erro_campo = "j76_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j76_codigo!=null){
       $sql .= " j76_codigo = $this->j76_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j76_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9600,'$this->j76_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j76_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1651,9600,'".AddSlashes(pg_result($resaco,$conresaco,'j76_codigo'))."','$this->j76_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j76_averbacao"]))
           $resac = db_query("insert into db_acount values($acount,1651,9601,'".AddSlashes(pg_result($resaco,$conresaco,'j76_averbacao'))."','$this->j76_averbacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j76_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,1651,9602,'".AddSlashes(pg_result($resaco,$conresaco,'j76_numcgm'))."','$this->j76_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j76_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1651,9603,'".AddSlashes(pg_result($resaco,$conresaco,'j76_tipo'))."','$this->j76_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j76_principal"]))
           $resac = db_query("insert into db_acount values($acount,1651,9604,'".AddSlashes(pg_result($resaco,$conresaco,'j76_principal'))."','$this->j76_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "novos promitentes ou proprietarios nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j76_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "novos promitentes ou proprietarios nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j76_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j76_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j76_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j76_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9600,'$j76_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1651,9600,'','".AddSlashes(pg_result($resaco,$iresaco,'j76_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1651,9601,'','".AddSlashes(pg_result($resaco,$iresaco,'j76_averbacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1651,9602,'','".AddSlashes(pg_result($resaco,$iresaco,'j76_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1651,9603,'','".AddSlashes(pg_result($resaco,$iresaco,'j76_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1651,9604,'','".AddSlashes(pg_result($resaco,$iresaco,'j76_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from averbacgm
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j76_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j76_codigo = $j76_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "novos promitentes ou proprietarios nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j76_codigo;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "novos promitentes ou proprietarios nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j76_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j76_codigo;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:averbacgm";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j76_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from averbacgm ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = averbacgm.j76_numcgm";
     $sql .= "      inner join averbacao  on  averbacao.j75_codigo = averbacgm.j76_averbacao";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = averbacao.j75_matric";
     $sql2 = "";
     if($dbwhere==""){
       if($j76_codigo!=null ){
         $sql2 .= " where averbacgm.j76_codigo = $j76_codigo "; 
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
   function sql_query_file ( $j76_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from averbacgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($j76_codigo!=null ){
         $sql2 .= " where averbacgm.j76_codigo = $j76_codigo "; 
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