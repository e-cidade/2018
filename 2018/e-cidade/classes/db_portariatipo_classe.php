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

//MODULO: recursoshumanos
//CLASSE DA ENTIDADE portariatipo
class cl_portariatipo { 
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
   var $h30_sequencial = 0; 
   var $h30_tipoasse = 0; 
   var $h30_portariaenvolv = 0; 
   var $h30_portariatipoato = 0; 
   var $h30_portariaproced = 0; 
   var $h30_amparolegal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h30_sequencial = int8 = Cod. Sequencial 
                 h30_tipoasse = int8 = Tipo de assentamento 
                 h30_portariaenvolv = int8 = Portaria Envolvida 
                 h30_portariatipoato = int8 = Tipo de ato de portaria 
                 h30_portariaproced = int8 = Procedimentos de Portaria 
                 h30_amparolegal = text = Amparo legal 
                 ";
   //funcao construtor da classe 
   function cl_portariatipo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("portariatipo"); 
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
       $this->h30_sequencial = ($this->h30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h30_sequencial"]:$this->h30_sequencial);
       $this->h30_tipoasse = ($this->h30_tipoasse == ""?@$GLOBALS["HTTP_POST_VARS"]["h30_tipoasse"]:$this->h30_tipoasse);
       $this->h30_portariaenvolv = ($this->h30_portariaenvolv == ""?@$GLOBALS["HTTP_POST_VARS"]["h30_portariaenvolv"]:$this->h30_portariaenvolv);
       $this->h30_portariatipoato = ($this->h30_portariatipoato == ""?@$GLOBALS["HTTP_POST_VARS"]["h30_portariatipoato"]:$this->h30_portariatipoato);
       $this->h30_portariaproced = ($this->h30_portariaproced == ""?@$GLOBALS["HTTP_POST_VARS"]["h30_portariaproced"]:$this->h30_portariaproced);
       $this->h30_amparolegal = ($this->h30_amparolegal == ""?@$GLOBALS["HTTP_POST_VARS"]["h30_amparolegal"]:$this->h30_amparolegal);
     }else{
       $this->h30_sequencial = ($this->h30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["h30_sequencial"]:$this->h30_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($h30_sequencial){ 
      $this->atualizacampos();
     if($this->h30_tipoasse == null ){ 
       $this->erro_sql = " Campo Tipo de assentamento nao Informado.";
       $this->erro_campo = "h30_tipoasse";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h30_portariaenvolv == null ){ 
       $this->erro_sql = " Campo Portaria Envolvida nao Informado.";
       $this->erro_campo = "h30_portariaenvolv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h30_portariatipoato == null ){ 
       $this->erro_sql = " Campo Tipo de ato de portaria nao Informado.";
       $this->erro_campo = "h30_portariatipoato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h30_portariaproced == null ){ 
       $this->erro_sql = " Campo Procedimentos de Portaria nao Informado.";
       $this->erro_campo = "h30_portariaproced";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($h30_sequencial == "" || $h30_sequencial == null ){
       $result = db_query("select nextval('portariatipo_h30_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: portariatipo_h30_sequencial_seq do campo: h30_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h30_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from portariatipo_h30_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $h30_sequencial)){
         $this->erro_sql = " Campo h30_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h30_sequencial = $h30_sequencial; 
       }
     }
     if(($this->h30_sequencial == null) || ($this->h30_sequencial == "") ){ 
       $this->erro_sql = " Campo h30_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into portariatipo(
                                       h30_sequencial 
                                      ,h30_tipoasse 
                                      ,h30_portariaenvolv 
                                      ,h30_portariatipoato 
                                      ,h30_portariaproced 
                                      ,h30_amparolegal 
                       )
                values (
                                $this->h30_sequencial 
                               ,$this->h30_tipoasse 
                               ,$this->h30_portariaenvolv 
                               ,$this->h30_portariatipoato 
                               ,$this->h30_portariaproced 
                               ,'$this->h30_amparolegal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipo de portaria ($this->h30_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipo de portaria já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipo de portaria ($this->h30_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h30_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h30_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10112,'$this->h30_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1740,10112,'','".AddSlashes(pg_result($resaco,0,'h30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1740,10113,'','".AddSlashes(pg_result($resaco,0,'h30_tipoasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1740,10114,'','".AddSlashes(pg_result($resaco,0,'h30_portariaenvolv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1740,10115,'','".AddSlashes(pg_result($resaco,0,'h30_portariatipoato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1740,10116,'','".AddSlashes(pg_result($resaco,0,'h30_portariaproced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1740,10117,'','".AddSlashes(pg_result($resaco,0,'h30_amparolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h30_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update portariatipo set ";
     $virgula = "";
     if(trim($this->h30_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h30_sequencial"])){ 
       $sql  .= $virgula." h30_sequencial = $this->h30_sequencial ";
       $virgula = ",";
       if(trim($this->h30_sequencial) == null ){ 
         $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
         $this->erro_campo = "h30_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h30_tipoasse)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h30_tipoasse"])){ 
       $sql  .= $virgula." h30_tipoasse = $this->h30_tipoasse ";
       $virgula = ",";
       if(trim($this->h30_tipoasse) == null ){ 
         $this->erro_sql = " Campo Tipo de assentamento nao Informado.";
         $this->erro_campo = "h30_tipoasse";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h30_portariaenvolv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h30_portariaenvolv"])){ 
       $sql  .= $virgula." h30_portariaenvolv = $this->h30_portariaenvolv ";
       $virgula = ",";
       if(trim($this->h30_portariaenvolv) == null ){ 
         $this->erro_sql = " Campo Portaria Envolvida nao Informado.";
         $this->erro_campo = "h30_portariaenvolv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h30_portariatipoato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h30_portariatipoato"])){ 
       $sql  .= $virgula." h30_portariatipoato = $this->h30_portariatipoato ";
       $virgula = ",";
       if(trim($this->h30_portariatipoato) == null ){ 
         $this->erro_sql = " Campo Tipo de ato de portaria nao Informado.";
         $this->erro_campo = "h30_portariatipoato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h30_portariaproced)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h30_portariaproced"])){ 
       $sql  .= $virgula." h30_portariaproced = $this->h30_portariaproced ";
       $virgula = ",";
       if(trim($this->h30_portariaproced) == null ){ 
         $this->erro_sql = " Campo Procedimentos de Portaria nao Informado.";
         $this->erro_campo = "h30_portariaproced";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h30_amparolegal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h30_amparolegal"])){ 
       $sql  .= $virgula." h30_amparolegal = '$this->h30_amparolegal' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($h30_sequencial!=null){
       $sql .= " h30_sequencial = $this->h30_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h30_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10112,'$this->h30_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h30_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1740,10112,'".AddSlashes(pg_result($resaco,$conresaco,'h30_sequencial'))."','$this->h30_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h30_tipoasse"]))
           $resac = db_query("insert into db_acount values($acount,1740,10113,'".AddSlashes(pg_result($resaco,$conresaco,'h30_tipoasse'))."','$this->h30_tipoasse',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h30_portariaenvolv"]))
           $resac = db_query("insert into db_acount values($acount,1740,10114,'".AddSlashes(pg_result($resaco,$conresaco,'h30_portariaenvolv'))."','$this->h30_portariaenvolv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h30_portariatipoato"]))
           $resac = db_query("insert into db_acount values($acount,1740,10115,'".AddSlashes(pg_result($resaco,$conresaco,'h30_portariatipoato'))."','$this->h30_portariatipoato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h30_portariaproced"]))
           $resac = db_query("insert into db_acount values($acount,1740,10116,'".AddSlashes(pg_result($resaco,$conresaco,'h30_portariaproced'))."','$this->h30_portariaproced',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h30_amparolegal"]))
           $resac = db_query("insert into db_acount values($acount,1740,10117,'".AddSlashes(pg_result($resaco,$conresaco,'h30_amparolegal'))."','$this->h30_amparolegal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de portaria nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h30_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de portaria nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h30_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h30_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10112,'$h30_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1740,10112,'','".AddSlashes(pg_result($resaco,$iresaco,'h30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1740,10113,'','".AddSlashes(pg_result($resaco,$iresaco,'h30_tipoasse'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1740,10114,'','".AddSlashes(pg_result($resaco,$iresaco,'h30_portariaenvolv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1740,10115,'','".AddSlashes(pg_result($resaco,$iresaco,'h30_portariatipoato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1740,10116,'','".AddSlashes(pg_result($resaco,$iresaco,'h30_portariaproced'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1740,10117,'','".AddSlashes(pg_result($resaco,$iresaco,'h30_amparolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from portariatipo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h30_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h30_sequencial = $h30_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo de portaria nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h30_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipo de portaria nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h30_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:portariatipo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h30_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from portariatipo ";
     $sql .= "      inner join tipoasse        on  tipoasse.h12_codigo = portariatipo.h30_tipoasse";
     $sql .= "      inner join portariaenvolv  on  portariaenvolv.h42_sequencial = portariatipo.h30_portariaenvolv";
     $sql .= "      inner join portariatipoato on  portariatipoato.h41_sequencial = portariatipo.h30_portariatipoato";
     $sql .= "      inner join portariaproced  on  portariaproced.h40_sequencial = portariatipo.h30_portariaproced";
//     $sql .= "      left  join portaria        on portaria.h31_portariatipo = portariatipo.h30_sequencial";
//     $sql .= "      left  join assenta         on assenta.h16_assent = portariatipo.h30_tipoasse";
     $sql2 = "";
     if($dbwhere==""){
       if($h30_sequencial!=null ){
         $sql2 .= " where portariatipo.h30_sequencial = $h30_sequencial "; 
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
   function sql_query_file ( $h30_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from portariatipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($h30_sequencial!=null ){
         $sql2 .= " where portariatipo.h30_sequencial = $h30_sequencial "; 
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
   function sql_query_func ( $h30_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from portariatipo ";
     $sql .= "      inner join tipoasse        on  tipoasse.h12_codigo = portariatipo.h30_tipoasse";
     $sql .= "      inner join portariaenvolv  on  portariaenvolv.h42_sequencial = portariatipo.h30_portariaenvolv";
     $sql .= "      inner join portariatipoato on  portariatipoato.h41_sequencial = portariatipo.h30_portariatipoato";
     $sql .= "      inner join portariaproced  on  portariaproced.h40_sequencial = portariatipo.h30_portariaproced";
     $sql2 = "";
     if($dbwhere==""){
       if($h30_sequencial!=null ){
         $sql2 .= " where portariatipo.h30_sequencial = $h30_sequencial "; 
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