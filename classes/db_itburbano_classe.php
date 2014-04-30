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

//MODULO: itbi
//CLASSE DA ENTIDADE itburbano
class cl_itburbano { 
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
   var $it05_guia = 0; 
   var $it05_frente = 0; 
   var $it05_fundos = 0; 
   var $it05_direito = 0; 
   var $it05_esquerdo = 0; 
   var $it05_itbisituacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 it05_guia = int8 = Número da guia de ITBI 
                 it05_frente = float8 = Frente 
                 it05_fundos = float8 = Fundos 
                 it05_direito = float8 = Lado Direito 
                 it05_esquerdo = float8 = Esquerdo 
                 it05_itbisituacao = int8 = Situação 
                 ";
   //funcao construtor da classe 
   function cl_itburbano() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itburbano"); 
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
       $this->it05_guia = ($this->it05_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it05_guia"]:$this->it05_guia);
       $this->it05_frente = ($this->it05_frente == ""?@$GLOBALS["HTTP_POST_VARS"]["it05_frente"]:$this->it05_frente);
       $this->it05_fundos = ($this->it05_fundos == ""?@$GLOBALS["HTTP_POST_VARS"]["it05_fundos"]:$this->it05_fundos);
       $this->it05_direito = ($this->it05_direito == ""?@$GLOBALS["HTTP_POST_VARS"]["it05_direito"]:$this->it05_direito);
       $this->it05_esquerdo = ($this->it05_esquerdo == ""?@$GLOBALS["HTTP_POST_VARS"]["it05_esquerdo"]:$this->it05_esquerdo);
       $this->it05_itbisituacao = ($this->it05_itbisituacao == ""?@$GLOBALS["HTTP_POST_VARS"]["it05_itbisituacao"]:$this->it05_itbisituacao);
     }else{
       $this->it05_guia = ($this->it05_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it05_guia"]:$this->it05_guia);
     }
   }
   // funcao para inclusao
   function incluir ($it05_guia){ 
      $this->atualizacampos();
     if($this->it05_frente == null ){ 
       $this->erro_sql = " Campo Frente nao Informado.";
       $this->erro_campo = "it05_frente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it05_fundos == null ){ 
       $this->erro_sql = " Campo Fundos nao Informado.";
       $this->erro_campo = "it05_fundos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it05_direito == null ){ 
       $this->erro_sql = " Campo Lado Direito nao Informado.";
       $this->erro_campo = "it05_direito";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it05_esquerdo == null ){ 
       $this->erro_sql = " Campo Esquerdo nao Informado.";
       $this->erro_campo = "it05_esquerdo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it05_itbisituacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "it05_itbisituacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->it05_guia = $it05_guia; 
     if(($this->it05_guia == null) || ($this->it05_guia == "") ){ 
       $this->erro_sql = " Campo it05_guia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itburbano(
                                       it05_guia 
                                      ,it05_frente 
                                      ,it05_fundos 
                                      ,it05_direito 
                                      ,it05_esquerdo 
                                      ,it05_itbisituacao 
                       )
                values (
                                $this->it05_guia 
                               ,$this->it05_frente 
                               ,$this->it05_fundos 
                               ,$this->it05_direito 
                               ,$this->it05_esquerdo 
                               ,$this->it05_itbisituacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "ITBI Urbano ($this->it05_guia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "ITBI Urbano já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "ITBI Urbano ($this->it05_guia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it05_guia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->it05_guia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5401,'$this->it05_guia','I')");
       $resac = db_query("insert into db_acount values($acount,796,5401,'','".AddSlashes(pg_result($resaco,0,'it05_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,796,5403,'','".AddSlashes(pg_result($resaco,0,'it05_frente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,796,5405,'','".AddSlashes(pg_result($resaco,0,'it05_fundos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,796,5404,'','".AddSlashes(pg_result($resaco,0,'it05_direito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,796,5406,'','".AddSlashes(pg_result($resaco,0,'it05_esquerdo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,796,5414,'','".AddSlashes(pg_result($resaco,0,'it05_itbisituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($it05_guia=null) { 
      $this->atualizacampos();
     $sql = " update itburbano set ";
     $virgula = "";
     if(trim($this->it05_guia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it05_guia"])){ 
       $sql  .= $virgula." it05_guia = $this->it05_guia ";
       $virgula = ",";
       if(trim($this->it05_guia) == null ){ 
         $this->erro_sql = " Campo Número da guia de ITBI nao Informado.";
         $this->erro_campo = "it05_guia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it05_frente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it05_frente"])){ 
       $sql  .= $virgula." it05_frente = $this->it05_frente ";
       $virgula = ",";
       if(trim($this->it05_frente) == null ){ 
         $this->erro_sql = " Campo Frente nao Informado.";
         $this->erro_campo = "it05_frente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it05_fundos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it05_fundos"])){ 
       $sql  .= $virgula." it05_fundos = $this->it05_fundos ";
       $virgula = ",";
       if(trim($this->it05_fundos) == null ){ 
         $this->erro_sql = " Campo Fundos nao Informado.";
         $this->erro_campo = "it05_fundos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it05_direito)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it05_direito"])){ 
       $sql  .= $virgula." it05_direito = $this->it05_direito ";
       $virgula = ",";
       if(trim($this->it05_direito) == null ){ 
         $this->erro_sql = " Campo Lado Direito nao Informado.";
         $this->erro_campo = "it05_direito";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it05_esquerdo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it05_esquerdo"])){ 
       $sql  .= $virgula." it05_esquerdo = $this->it05_esquerdo ";
       $virgula = ",";
       if(trim($this->it05_esquerdo) == null ){ 
         $this->erro_sql = " Campo Esquerdo nao Informado.";
         $this->erro_campo = "it05_esquerdo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it05_itbisituacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it05_itbisituacao"])){ 
       $sql  .= $virgula." it05_itbisituacao = $this->it05_itbisituacao ";
       $virgula = ",";
       if(trim($this->it05_itbisituacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "it05_itbisituacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($it05_guia!=null){
       $sql .= " it05_guia = $this->it05_guia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->it05_guia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5401,'$this->it05_guia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it05_guia"]))
           $resac = db_query("insert into db_acount values($acount,796,5401,'".AddSlashes(pg_result($resaco,$conresaco,'it05_guia'))."','$this->it05_guia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it05_frente"]))
           $resac = db_query("insert into db_acount values($acount,796,5403,'".AddSlashes(pg_result($resaco,$conresaco,'it05_frente'))."','$this->it05_frente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it05_fundos"]))
           $resac = db_query("insert into db_acount values($acount,796,5405,'".AddSlashes(pg_result($resaco,$conresaco,'it05_fundos'))."','$this->it05_fundos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it05_direito"]))
           $resac = db_query("insert into db_acount values($acount,796,5404,'".AddSlashes(pg_result($resaco,$conresaco,'it05_direito'))."','$this->it05_direito',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it05_esquerdo"]))
           $resac = db_query("insert into db_acount values($acount,796,5406,'".AddSlashes(pg_result($resaco,$conresaco,'it05_esquerdo'))."','$this->it05_esquerdo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it05_itbisituacao"]))
           $resac = db_query("insert into db_acount values($acount,796,5414,'".AddSlashes(pg_result($resaco,$conresaco,'it05_itbisituacao'))."','$this->it05_itbisituacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ITBI Urbano nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it05_guia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ITBI Urbano nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it05_guia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it05_guia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($it05_guia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($it05_guia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5401,'$it05_guia','E')");
         $resac = db_query("insert into db_acount values($acount,796,5401,'','".AddSlashes(pg_result($resaco,$iresaco,'it05_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,796,5403,'','".AddSlashes(pg_result($resaco,$iresaco,'it05_frente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,796,5405,'','".AddSlashes(pg_result($resaco,$iresaco,'it05_fundos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,796,5404,'','".AddSlashes(pg_result($resaco,$iresaco,'it05_direito'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,796,5406,'','".AddSlashes(pg_result($resaco,$iresaco,'it05_esquerdo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,796,5414,'','".AddSlashes(pg_result($resaco,$iresaco,'it05_itbisituacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from itburbano
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it05_guia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it05_guia = $it05_guia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "ITBI Urbano nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it05_guia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "ITBI Urbano nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it05_guia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it05_guia;
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
        $this->erro_sql   = "Record Vazio na Tabela:itburbano";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $it05_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itburbano ";
     $sql .= "      inner join itbi 		   on  itbi.it01_guia		     = itburbano.it05_guia";
     $sql .= "      inner join itbisituacao    on  itbisituacao.it07_codigo  = itburbano.it05_itbisituacao";
     $sql .= "      inner join itbitransacao   on  itbitransacao.it04_codigo = itbi.it01_tipotransacao";
     $sql .= "      inner join itbimatric 	   on  itbimatric.it06_guia 	 = itburbano.it05_guia";
     
     $sql2 = "";
     if($dbwhere==""){
       if($it05_guia!=null ){
         $sql2 .= " where itburbano.it05_guia = $it05_guia "; 
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
   function sql_query_file ( $it05_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itburbano ";
     $sql2 = "";
     if($dbwhere==""){
       if($it05_guia!=null ){
         $sql2 .= " where itburbano.it05_guia = $it05_guia "; 
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
  
   function sql_query_dados( $it05_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itburbano ";
     $sql .= "      inner join itbi 		   on  itbi.it01_guia		     = itburbano.it05_guia";
     $sql .= "      inner join itbidadosimovel on  itbidadosimovel.it22_itbi = itburbano.it05_guia";
     $sql .= "      inner join itbisituacao    on  itbisituacao.it07_codigo  = itburbano.it05_itbisituacao";
     $sql .= "      inner join itbitransacao   on  itbitransacao.it04_codigo = itbi.it01_tipotransacao";
     $sql .= "      inner join itbimatric 	   on  itbimatric.it06_guia 	 = itburbano.it05_guia";
     
     $sql2 = "";
     if($dbwhere==""){
       if($it05_guia!=null ){
         $sql2 .= " where itburbano.it05_guia = $it05_guia "; 
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