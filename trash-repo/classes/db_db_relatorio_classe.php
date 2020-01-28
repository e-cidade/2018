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

//MODULO: Configuracoes
//CLASSE DA ENTIDADE db_relatorio
class cl_db_relatorio { 
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
   var $db63_sequencial = 0; 
   var $db63_db_gruporelatorio = 0; 
   var $db63_db_tiporelatorio = 0; 
   var $db63_nomerelatorio = null; 
   var $db63_versao_xml = null; 
   var $db63_data_dia = null; 
   var $db63_data_mes = null; 
   var $db63_data_ano = null; 
   var $db63_data = null; 
   var $db63_xmlestruturarel = null; 
   var $db63_db_relatorioorigem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db63_sequencial = int4 = Sequencial 
                 db63_db_gruporelatorio = int4 = Grupo do relatório 
                 db63_db_tiporelatorio = int4 = Tipo de relatório 
                 db63_nomerelatorio = varchar(50) = Nome relatório 
                 db63_versao_xml = varchar(40) = Versão XML 
                 db63_data = date = Data do relatório 
                 db63_xmlestruturarel = text = Estrutuda do XML 
                 db63_db_relatorioorigem = int4 = Origem Relatório 
                 ";
   //funcao construtor da classe 
   function cl_db_relatorio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_relatorio"); 
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
       $this->db63_sequencial = ($this->db63_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db63_sequencial"]:$this->db63_sequencial);
       $this->db63_db_gruporelatorio = ($this->db63_db_gruporelatorio == ""?@$GLOBALS["HTTP_POST_VARS"]["db63_db_gruporelatorio"]:$this->db63_db_gruporelatorio);
       $this->db63_db_tiporelatorio = ($this->db63_db_tiporelatorio == ""?@$GLOBALS["HTTP_POST_VARS"]["db63_db_tiporelatorio"]:$this->db63_db_tiporelatorio);
       $this->db63_nomerelatorio = ($this->db63_nomerelatorio == ""?@$GLOBALS["HTTP_POST_VARS"]["db63_nomerelatorio"]:$this->db63_nomerelatorio);
       $this->db63_versao_xml = ($this->db63_versao_xml == ""?@$GLOBALS["HTTP_POST_VARS"]["db63_versao_xml"]:$this->db63_versao_xml);
       if($this->db63_data == ""){
         $this->db63_data_dia = ($this->db63_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["db63_data_dia"]:$this->db63_data_dia);
         $this->db63_data_mes = ($this->db63_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["db63_data_mes"]:$this->db63_data_mes);
         $this->db63_data_ano = ($this->db63_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["db63_data_ano"]:$this->db63_data_ano);
         if($this->db63_data_dia != ""){
            $this->db63_data = $this->db63_data_ano."-".$this->db63_data_mes."-".$this->db63_data_dia;
         }
       }
       $this->db63_xmlestruturarel = ($this->db63_xmlestruturarel == ""?@$GLOBALS["HTTP_POST_VARS"]["db63_xmlestruturarel"]:$this->db63_xmlestruturarel);
       $this->db63_db_relatorioorigem = ($this->db63_db_relatorioorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["db63_db_relatorioorigem"]:$this->db63_db_relatorioorigem);
     }else{
       $this->db63_sequencial = ($this->db63_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db63_sequencial"]:$this->db63_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db63_sequencial){ 
      $this->atualizacampos();
     if($this->db63_db_gruporelatorio == null ){ 
       $this->erro_sql = " Campo Grupo do relatório nao Informado.";
       $this->erro_campo = "db63_db_gruporelatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db63_db_tiporelatorio == null ){ 
       $this->erro_sql = " Campo Tipo de relatório nao Informado.";
       $this->erro_campo = "db63_db_tiporelatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db63_nomerelatorio == null ){ 
       $this->erro_sql = " Campo Nome relatório nao Informado.";
       $this->erro_campo = "db63_nomerelatorio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db63_versao_xml == null ){ 
       $this->erro_sql = " Campo Versão XML nao Informado.";
       $this->erro_campo = "db63_versao_xml";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db63_data == null ){ 
       $this->erro_sql = " Campo Data do relatório nao Informado.";
       $this->erro_campo = "db63_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db63_xmlestruturarel == null ){ 
       $this->erro_sql = " Campo Estrutuda do XML nao Informado.";
       $this->erro_campo = "db63_xmlestruturarel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db63_db_relatorioorigem == null ){ 
       $this->erro_sql = " Campo Origem Relatório nao Informado.";
       $this->erro_campo = "db63_db_relatorioorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db63_sequencial == "" || $db63_sequencial == null ){
       $result = db_query("select nextval('db_relatorio_db63_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_relatorio_db63_sequencial_seq do campo: db63_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db63_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_relatorio_db63_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db63_sequencial)){
         $this->erro_sql = " Campo db63_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db63_sequencial = $db63_sequencial; 
       }
     }
     if(($this->db63_sequencial == null) || ($this->db63_sequencial == "") ){ 
       $this->erro_sql = " Campo db63_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_relatorio(
                                       db63_sequencial 
                                      ,db63_db_gruporelatorio 
                                      ,db63_db_tiporelatorio 
                                      ,db63_nomerelatorio 
                                      ,db63_versao_xml 
                                      ,db63_data 
                                      ,db63_xmlestruturarel 
                                      ,db63_db_relatorioorigem 
                       )
                values (
                                $this->db63_sequencial 
                               ,$this->db63_db_gruporelatorio 
                               ,$this->db63_db_tiporelatorio 
                               ,'$this->db63_nomerelatorio' 
                               ,'$this->db63_versao_xml' 
                               ,".($this->db63_data == "null" || $this->db63_data == ""?"null":"'".$this->db63_data."'")." 
                               ,'$this->db63_xmlestruturarel' 
                               ,$this->db63_db_relatorioorigem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Relatório ($this->db63_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Relatório já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Relatório ($this->db63_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db63_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db63_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12277,'$this->db63_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2134,12277,'','".AddSlashes(pg_result($resaco,0,'db63_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2134,12281,'','".AddSlashes(pg_result($resaco,0,'db63_db_gruporelatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2134,12282,'','".AddSlashes(pg_result($resaco,0,'db63_db_tiporelatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2134,12267,'','".AddSlashes(pg_result($resaco,0,'db63_nomerelatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2134,12268,'','".AddSlashes(pg_result($resaco,0,'db63_versao_xml'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2134,12269,'','".AddSlashes(pg_result($resaco,0,'db63_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2134,12270,'','".AddSlashes(pg_result($resaco,0,'db63_xmlestruturarel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2134,14175,'','".AddSlashes(pg_result($resaco,0,'db63_db_relatorioorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db63_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_relatorio set ";
     $virgula = "";
     if(trim($this->db63_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db63_sequencial"])){ 
       $sql  .= $virgula." db63_sequencial = $this->db63_sequencial ";
       $virgula = ",";
       if(trim($this->db63_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db63_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db63_db_gruporelatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db63_db_gruporelatorio"])){ 
       $sql  .= $virgula." db63_db_gruporelatorio = $this->db63_db_gruporelatorio ";
       $virgula = ",";
       if(trim($this->db63_db_gruporelatorio) == null ){ 
         $this->erro_sql = " Campo Grupo do relatório nao Informado.";
         $this->erro_campo = "db63_db_gruporelatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db63_db_tiporelatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db63_db_tiporelatorio"])){ 
       $sql  .= $virgula." db63_db_tiporelatorio = $this->db63_db_tiporelatorio ";
       $virgula = ",";
       if(trim($this->db63_db_tiporelatorio) == null ){ 
         $this->erro_sql = " Campo Tipo de relatório nao Informado.";
         $this->erro_campo = "db63_db_tiporelatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db63_nomerelatorio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db63_nomerelatorio"])){ 
       $sql  .= $virgula." db63_nomerelatorio = '$this->db63_nomerelatorio' ";
       $virgula = ",";
       if(trim($this->db63_nomerelatorio) == null ){ 
         $this->erro_sql = " Campo Nome relatório nao Informado.";
         $this->erro_campo = "db63_nomerelatorio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db63_versao_xml)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db63_versao_xml"])){ 
       $sql  .= $virgula." db63_versao_xml = '$this->db63_versao_xml' ";
       $virgula = ",";
       if(trim($this->db63_versao_xml) == null ){ 
         $this->erro_sql = " Campo Versão XML nao Informado.";
         $this->erro_campo = "db63_versao_xml";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db63_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db63_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["db63_data_dia"] !="") ){ 
       $sql  .= $virgula." db63_data = '$this->db63_data' ";
       $virgula = ",";
       if(trim($this->db63_data) == null ){ 
         $this->erro_sql = " Campo Data do relatório nao Informado.";
         $this->erro_campo = "db63_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["db63_data_dia"])){ 
         $sql  .= $virgula." db63_data = null ";
         $virgula = ",";
         if(trim($this->db63_data) == null ){ 
           $this->erro_sql = " Campo Data do relatório nao Informado.";
           $this->erro_campo = "db63_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->db63_xmlestruturarel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db63_xmlestruturarel"])){ 
       $sql  .= $virgula." db63_xmlestruturarel = '$this->db63_xmlestruturarel' ";
       $virgula = ",";
       if(trim($this->db63_xmlestruturarel) == null ){ 
         $this->erro_sql = " Campo Estrutuda do XML nao Informado.";
         $this->erro_campo = "db63_xmlestruturarel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db63_db_relatorioorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db63_db_relatorioorigem"])){ 
       $sql  .= $virgula." db63_db_relatorioorigem = $this->db63_db_relatorioorigem ";
       $virgula = ",";
       if(trim($this->db63_db_relatorioorigem) == null ){ 
         $this->erro_sql = " Campo Origem Relatório nao Informado.";
         $this->erro_campo = "db63_db_relatorioorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db63_sequencial!=null){
       $sql .= " db63_sequencial = $this->db63_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db63_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12277,'$this->db63_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db63_sequencial"]) || $this->db63_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2134,12277,'".AddSlashes(pg_result($resaco,$conresaco,'db63_sequencial'))."','$this->db63_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db63_db_gruporelatorio"]) || $this->db63_db_gruporelatorio != "")
           $resac = db_query("insert into db_acount values($acount,2134,12281,'".AddSlashes(pg_result($resaco,$conresaco,'db63_db_gruporelatorio'))."','$this->db63_db_gruporelatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db63_db_tiporelatorio"]) || $this->db63_db_tiporelatorio != "")
           $resac = db_query("insert into db_acount values($acount,2134,12282,'".AddSlashes(pg_result($resaco,$conresaco,'db63_db_tiporelatorio'))."','$this->db63_db_tiporelatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db63_nomerelatorio"]) || $this->db63_nomerelatorio != "")
           $resac = db_query("insert into db_acount values($acount,2134,12267,'".AddSlashes(pg_result($resaco,$conresaco,'db63_nomerelatorio'))."','$this->db63_nomerelatorio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db63_versao_xml"]) || $this->db63_versao_xml != "")
           $resac = db_query("insert into db_acount values($acount,2134,12268,'".AddSlashes(pg_result($resaco,$conresaco,'db63_versao_xml'))."','$this->db63_versao_xml',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db63_data"]) || $this->db63_data != "")
           $resac = db_query("insert into db_acount values($acount,2134,12269,'".AddSlashes(pg_result($resaco,$conresaco,'db63_data'))."','$this->db63_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db63_xmlestruturarel"]) || $this->db63_xmlestruturarel != "")
           $resac = db_query("insert into db_acount values($acount,2134,12270,'".AddSlashes(pg_result($resaco,$conresaco,'db63_xmlestruturarel'))."','$this->db63_xmlestruturarel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db63_db_relatorioorigem"]) || $this->db63_db_relatorioorigem != "")
           $resac = db_query("insert into db_acount values($acount,2134,14175,'".AddSlashes(pg_result($resaco,$conresaco,'db63_db_relatorioorigem'))."','$this->db63_db_relatorioorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Relatório nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db63_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Relatório nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db63_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db63_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12277,'$db63_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2134,12277,'','".AddSlashes(pg_result($resaco,$iresaco,'db63_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2134,12281,'','".AddSlashes(pg_result($resaco,$iresaco,'db63_db_gruporelatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2134,12282,'','".AddSlashes(pg_result($resaco,$iresaco,'db63_db_tiporelatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2134,12267,'','".AddSlashes(pg_result($resaco,$iresaco,'db63_nomerelatorio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2134,12268,'','".AddSlashes(pg_result($resaco,$iresaco,'db63_versao_xml'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2134,12269,'','".AddSlashes(pg_result($resaco,$iresaco,'db63_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2134,12270,'','".AddSlashes(pg_result($resaco,$iresaco,'db63_xmlestruturarel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2134,14175,'','".AddSlashes(pg_result($resaco,$iresaco,'db63_db_relatorioorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_relatorio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db63_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db63_sequencial = $db63_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Relatório nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db63_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Relatório nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db63_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db63_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_relatorio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db63_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_relatorio ";
     $sql .= "      inner join db_gruporelatorio  on  db_gruporelatorio.db13_sequencial = db_relatorio.db63_db_gruporelatorio";
     $sql .= "      inner join db_tiporelatorio  on  db_tiporelatorio.db14_sequencial = db_relatorio.db63_db_tiporelatorio";
     $sql .= "      inner join db_relatorioorigem  on  db_relatorioorigem.db16_sequencial = db_relatorio.db63_db_relatorioorigem";
     $sql2 = "";
     if($dbwhere==""){
       if($db63_sequencial!=null ){
         $sql2 .= " where db_relatorio.db63_sequencial = $db63_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $db63_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_relatorio ";
     $sql2 = "";
     if($dbwhere==""){
       if($db63_sequencial!=null ){
         $sql2 .= " where db_relatorio.db63_sequencial = $db63_sequencial "; 
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