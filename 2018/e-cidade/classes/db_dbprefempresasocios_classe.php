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

//MODULO: prefeitura
//CLASSE DA ENTIDADE dbprefempresasocios
class cl_dbprefempresasocios { 
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
   var $q66_sequencial = 0; 
   var $q66_dbprefcgm = 0; 
   var $q66_dbprefempresa = 0; 
   var $q66_tipocapital = 0; 
   var $q66_tipo = 0; 
   var $q66_capital = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q66_sequencial = int4 = Codigo Sequencial 
                 q66_dbprefcgm = int4 = Código sequencial 
                 q66_dbprefempresa = int4 = Código sequencial 
                 q66_tipocapital = int4 = Tipo de capital 
                 q66_tipo = int4 = Tipo 
                 q66_capital = float8 = Capital 
                 ";
   //funcao construtor da classe 
   function cl_dbprefempresasocios() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("dbprefempresasocios"); 
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
       $this->q66_sequencial = ($this->q66_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q66_sequencial"]:$this->q66_sequencial);
       $this->q66_dbprefcgm = ($this->q66_dbprefcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["q66_dbprefcgm"]:$this->q66_dbprefcgm);
       $this->q66_dbprefempresa = ($this->q66_dbprefempresa == ""?@$GLOBALS["HTTP_POST_VARS"]["q66_dbprefempresa"]:$this->q66_dbprefempresa);
       $this->q66_tipocapital = ($this->q66_tipocapital == ""?@$GLOBALS["HTTP_POST_VARS"]["q66_tipocapital"]:$this->q66_tipocapital);
       $this->q66_tipo = ($this->q66_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["q66_tipo"]:$this->q66_tipo);
       $this->q66_capital = ($this->q66_capital == ""?@$GLOBALS["HTTP_POST_VARS"]["q66_capital"]:$this->q66_capital);
     }else{
       $this->q66_sequencial = ($this->q66_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q66_sequencial"]:$this->q66_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q66_sequencial){ 
      $this->atualizacampos();
     if($this->q66_dbprefcgm == null ){ 
       $this->erro_sql = " Campo Código sequencial nao Informado.";
       $this->erro_campo = "q66_dbprefcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q66_dbprefempresa == null ){ 
       $this->erro_sql = " Campo Código sequencial nao Informado.";
       $this->erro_campo = "q66_dbprefempresa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q66_tipocapital == null ){ 
       $this->erro_sql = " Campo Tipo de capital nao Informado.";
       $this->erro_campo = "q66_tipocapital";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q66_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "q66_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q66_capital == null ){ 
       $this->erro_sql = " Campo Capital nao Informado.";
       $this->erro_campo = "q66_capital";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q66_sequencial == "" || $q66_sequencial == null ){
       $result = db_query("select nextval('dbprefempresasocios_q66_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: dbprefempresasocios_q66_sequencial_seq do campo: q66_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q66_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from dbprefempresasocios_q66_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q66_sequencial)){
         $this->erro_sql = " Campo q66_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q66_sequencial = $q66_sequencial; 
       }
     }
     if(($this->q66_sequencial == null) || ($this->q66_sequencial == "") ){ 
       $this->erro_sql = " Campo q66_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into dbprefempresasocios(
                                       q66_sequencial 
                                      ,q66_dbprefcgm 
                                      ,q66_dbprefempresa 
                                      ,q66_tipocapital 
                                      ,q66_tipo 
                                      ,q66_capital 
                       )
                values (
                                $this->q66_sequencial 
                               ,$this->q66_dbprefcgm 
                               ,$this->q66_dbprefempresa 
                               ,$this->q66_tipocapital 
                               ,$this->q66_tipo 
                               ,$this->q66_capital 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de socios offline ($this->q66_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de socios offline já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de socios offline ($this->q66_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q66_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q66_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10233,'$this->q66_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1767,10233,'','".AddSlashes(pg_result($resaco,0,'q66_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1767,10234,'','".AddSlashes(pg_result($resaco,0,'q66_dbprefcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1767,10235,'','".AddSlashes(pg_result($resaco,0,'q66_dbprefempresa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1767,10236,'','".AddSlashes(pg_result($resaco,0,'q66_tipocapital'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1767,10238,'','".AddSlashes(pg_result($resaco,0,'q66_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1767,10237,'','".AddSlashes(pg_result($resaco,0,'q66_capital'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q66_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update dbprefempresasocios set ";
     $virgula = "";
     if(trim($this->q66_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q66_sequencial"])){ 
       $sql  .= $virgula." q66_sequencial = $this->q66_sequencial ";
       $virgula = ",";
       if(trim($this->q66_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
         $this->erro_campo = "q66_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q66_dbprefcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q66_dbprefcgm"])){ 
       $sql  .= $virgula." q66_dbprefcgm = $this->q66_dbprefcgm ";
       $virgula = ",";
       if(trim($this->q66_dbprefcgm) == null ){ 
         $this->erro_sql = " Campo Código sequencial nao Informado.";
         $this->erro_campo = "q66_dbprefcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q66_dbprefempresa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q66_dbprefempresa"])){ 
       $sql  .= $virgula." q66_dbprefempresa = $this->q66_dbprefempresa ";
       $virgula = ",";
       if(trim($this->q66_dbprefempresa) == null ){ 
         $this->erro_sql = " Campo Código sequencial nao Informado.";
         $this->erro_campo = "q66_dbprefempresa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q66_tipocapital)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q66_tipocapital"])){ 
       $sql  .= $virgula." q66_tipocapital = $this->q66_tipocapital ";
       $virgula = ",";
       if(trim($this->q66_tipocapital) == null ){ 
         $this->erro_sql = " Campo Tipo de capital nao Informado.";
         $this->erro_campo = "q66_tipocapital";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q66_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q66_tipo"])){ 
       $sql  .= $virgula." q66_tipo = $this->q66_tipo ";
       $virgula = ",";
       if(trim($this->q66_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "q66_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q66_capital)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q66_capital"])){ 
       $sql  .= $virgula." q66_capital = $this->q66_capital ";
       $virgula = ",";
       if(trim($this->q66_capital) == null ){ 
         $this->erro_sql = " Campo Capital nao Informado.";
         $this->erro_campo = "q66_capital";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q66_sequencial!=null){
       $sql .= " q66_sequencial = $this->q66_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q66_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10233,'$this->q66_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q66_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1767,10233,'".AddSlashes(pg_result($resaco,$conresaco,'q66_sequencial'))."','$this->q66_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q66_dbprefcgm"]))
           $resac = db_query("insert into db_acount values($acount,1767,10234,'".AddSlashes(pg_result($resaco,$conresaco,'q66_dbprefcgm'))."','$this->q66_dbprefcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q66_dbprefempresa"]))
           $resac = db_query("insert into db_acount values($acount,1767,10235,'".AddSlashes(pg_result($resaco,$conresaco,'q66_dbprefempresa'))."','$this->q66_dbprefempresa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q66_tipocapital"]))
           $resac = db_query("insert into db_acount values($acount,1767,10236,'".AddSlashes(pg_result($resaco,$conresaco,'q66_tipocapital'))."','$this->q66_tipocapital',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q66_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1767,10238,'".AddSlashes(pg_result($resaco,$conresaco,'q66_tipo'))."','$this->q66_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q66_capital"]))
           $resac = db_query("insert into db_acount values($acount,1767,10237,'".AddSlashes(pg_result($resaco,$conresaco,'q66_capital'))."','$this->q66_capital',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de socios offline nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q66_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de socios offline nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q66_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q66_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10233,'$q66_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1767,10233,'','".AddSlashes(pg_result($resaco,$iresaco,'q66_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1767,10234,'','".AddSlashes(pg_result($resaco,$iresaco,'q66_dbprefcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1767,10235,'','".AddSlashes(pg_result($resaco,$iresaco,'q66_dbprefempresa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1767,10236,'','".AddSlashes(pg_result($resaco,$iresaco,'q66_tipocapital'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1767,10238,'','".AddSlashes(pg_result($resaco,$iresaco,'q66_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1767,10237,'','".AddSlashes(pg_result($resaco,$iresaco,'q66_capital'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from dbprefempresasocios
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q66_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q66_sequencial = $q66_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de socios offline nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q66_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de socios offline nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q66_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:dbprefempresasocios";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $q66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from dbprefempresasocios ";
     $sql .= "      inner join dbprefcgm  on  dbprefcgm.z01_sequencial = dbprefempresasocios.q66_dbprefcgm";
     $sql .= "      inner join dbprefempresa  on  dbprefempresa.q55_sequencial = dbprefempresasocios.q66_dbprefempresa";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = dbprefempresa.q55_usuario";
     $sql .= "      inner join issporte  on  issporte.q40_codporte = dbprefempresa.q55_issporte";
     $sql .= "      inner join dbprefcgm  on  dbprefcgm.z01_sequencial = dbprefempresa.q55_dbprefcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($q66_sequencial!=null ){
         $sql2 .= " where dbprefempresasocios.q66_sequencial = $q66_sequencial "; 
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
   function sql_query_file ( $q66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from dbprefempresasocios ";
     $sql2 = "";
     if($dbwhere==""){
       if($q66_sequencial!=null ){
         $sql2 .= " where dbprefempresasocios.q66_sequencial = $q66_sequencial "; 
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