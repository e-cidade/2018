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

//MODULO: atendimento
//CLASSE DA ENTIDADE db_projetosativcli
class cl_db_projetosativcli { 
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
   var $at64_sequencial = 0; 
   var $at64_codproj = 0; 
   var $at64_codativ = 0; 
   var $at64_dtini_dia = null; 
   var $at64_dtini_mes = null; 
   var $at64_dtini_ano = null; 
   var $at64_dtini = null; 
   var $at64_dtfim_dia = null; 
   var $at64_dtfim_mes = null; 
   var $at64_dtfim_ano = null; 
   var $at64_dtfim = null; 
   var $at64_situacao = 0; 
   var $at64_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at64_sequencial = int4 = Sequencial 
                 at64_codproj = int4 = Codigo do projeto 
                 at64_codativ = int4 = Código Atividade 
                 at64_dtini = date = Data Inicio 
                 at64_dtfim = date = Data Final 
                 at64_situacao = int4 = Código 
                 at64_descricao = text = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_db_projetosativcli() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_projetosativcli"); 
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
       $this->at64_sequencial = ($this->at64_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at64_sequencial"]:$this->at64_sequencial);
       $this->at64_codproj = ($this->at64_codproj == ""?@$GLOBALS["HTTP_POST_VARS"]["at64_codproj"]:$this->at64_codproj);
       $this->at64_codativ = ($this->at64_codativ == ""?@$GLOBALS["HTTP_POST_VARS"]["at64_codativ"]:$this->at64_codativ);
       if($this->at64_dtini == ""){
         $this->at64_dtini_dia = ($this->at64_dtini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at64_dtini_dia"]:$this->at64_dtini_dia);
         $this->at64_dtini_mes = ($this->at64_dtini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at64_dtini_mes"]:$this->at64_dtini_mes);
         $this->at64_dtini_ano = ($this->at64_dtini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at64_dtini_ano"]:$this->at64_dtini_ano);
         if($this->at64_dtini_dia != ""){
            $this->at64_dtini = $this->at64_dtini_ano."-".$this->at64_dtini_mes."-".$this->at64_dtini_dia;
         }
       }
       if($this->at64_dtfim == ""){
         $this->at64_dtfim_dia = ($this->at64_dtfim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at64_dtfim_dia"]:$this->at64_dtfim_dia);
         $this->at64_dtfim_mes = ($this->at64_dtfim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at64_dtfim_mes"]:$this->at64_dtfim_mes);
         $this->at64_dtfim_ano = ($this->at64_dtfim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at64_dtfim_ano"]:$this->at64_dtfim_ano);
         if($this->at64_dtfim_dia != ""){
            $this->at64_dtfim = $this->at64_dtfim_ano."-".$this->at64_dtfim_mes."-".$this->at64_dtfim_dia;
         }
       }
       $this->at64_situacao = ($this->at64_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["at64_situacao"]:$this->at64_situacao);
       $this->at64_descricao = ($this->at64_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["at64_descricao"]:$this->at64_descricao);
     }else{
       $this->at64_sequencial = ($this->at64_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at64_sequencial"]:$this->at64_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($at64_sequencial){ 
      $this->atualizacampos();
     if($this->at64_codproj == null ){ 
       $this->erro_sql = " Campo Codigo do projeto nao Informado.";
       $this->erro_campo = "at64_codproj";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at64_codativ == null ){ 
       $this->erro_sql = " Campo Código Atividade nao Informado.";
       $this->erro_campo = "at64_codativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at64_dtini == null ){ 
       $this->erro_sql = " Campo Data Inicio nao Informado.";
       $this->erro_campo = "at64_dtini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at64_dtfim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "at64_dtfim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at64_situacao == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "at64_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at64_sequencial == "" || $at64_sequencial == null ){
       $result = db_query("select nextval('db_projetosclientes_at64_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_projetosclientes_at64_sequencial_seq do campo: at64_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at64_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_projetosclientes_at64_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $at64_sequencial)){
         $this->erro_sql = " Campo at64_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at64_sequencial = $at64_sequencial; 
       }
     }
     if(($this->at64_sequencial == null) || ($this->at64_sequencial == "") ){ 
       $this->erro_sql = " Campo at64_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_projetosativcli(
                                       at64_sequencial 
                                      ,at64_codproj 
                                      ,at64_codativ 
                                      ,at64_dtini 
                                      ,at64_dtfim 
                                      ,at64_situacao 
                                      ,at64_descricao 
                       )
                values (
                                $this->at64_sequencial 
                               ,$this->at64_codproj 
                               ,$this->at64_codativ 
                               ,".($this->at64_dtini == "null" || $this->at64_dtini == ""?"null":"'".$this->at64_dtini."'")." 
                               ,".($this->at64_dtfim == "null" || $this->at64_dtfim == ""?"null":"'".$this->at64_dtfim."'")." 
                               ,$this->at64_situacao 
                               ,'$this->at64_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atividades dos Clientes nos projetos ($this->at64_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atividades dos Clientes nos projetos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atividades dos Clientes nos projetos ($this->at64_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at64_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at64_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8358,'$this->at64_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1414,8358,'','".AddSlashes(pg_result($resaco,0,'at64_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1414,8359,'','".AddSlashes(pg_result($resaco,0,'at64_codproj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1414,8360,'','".AddSlashes(pg_result($resaco,0,'at64_codativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1414,9740,'','".AddSlashes(pg_result($resaco,0,'at64_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1414,9741,'','".AddSlashes(pg_result($resaco,0,'at64_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1414,8352,'','".AddSlashes(pg_result($resaco,0,'at64_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1414,11855,'','".AddSlashes(pg_result($resaco,0,'at64_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at64_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_projetosativcli set ";
     $virgula = "";
     if(trim($this->at64_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at64_sequencial"])){ 
       $sql  .= $virgula." at64_sequencial = $this->at64_sequencial ";
       $virgula = ",";
       if(trim($this->at64_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "at64_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at64_codproj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at64_codproj"])){ 
       $sql  .= $virgula." at64_codproj = $this->at64_codproj ";
       $virgula = ",";
       if(trim($this->at64_codproj) == null ){ 
         $this->erro_sql = " Campo Codigo do projeto nao Informado.";
         $this->erro_campo = "at64_codproj";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at64_codativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at64_codativ"])){ 
       $sql  .= $virgula." at64_codativ = $this->at64_codativ ";
       $virgula = ",";
       if(trim($this->at64_codativ) == null ){ 
         $this->erro_sql = " Campo Código Atividade nao Informado.";
         $this->erro_campo = "at64_codativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at64_dtini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at64_dtini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at64_dtini_dia"] !="") ){ 
       $sql  .= $virgula." at64_dtini = '$this->at64_dtini' ";
       $virgula = ",";
       if(trim($this->at64_dtini) == null ){ 
         $this->erro_sql = " Campo Data Inicio nao Informado.";
         $this->erro_campo = "at64_dtini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at64_dtini_dia"])){ 
         $sql  .= $virgula." at64_dtini = null ";
         $virgula = ",";
         if(trim($this->at64_dtini) == null ){ 
           $this->erro_sql = " Campo Data Inicio nao Informado.";
           $this->erro_campo = "at64_dtini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at64_dtfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at64_dtfim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at64_dtfim_dia"] !="") ){ 
       $sql  .= $virgula." at64_dtfim = '$this->at64_dtfim' ";
       $virgula = ",";
       if(trim($this->at64_dtfim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "at64_dtfim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at64_dtfim_dia"])){ 
         $sql  .= $virgula." at64_dtfim = null ";
         $virgula = ",";
         if(trim($this->at64_dtfim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "at64_dtfim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at64_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at64_situacao"])){ 
       $sql  .= $virgula." at64_situacao = $this->at64_situacao ";
       $virgula = ",";
       if(trim($this->at64_situacao) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "at64_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at64_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at64_descricao"])){ 
       $sql  .= $virgula." at64_descricao = '$this->at64_descricao' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($at64_sequencial!=null){
       $sql .= " at64_sequencial = $this->at64_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at64_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8358,'$this->at64_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at64_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1414,8358,'".AddSlashes(pg_result($resaco,$conresaco,'at64_sequencial'))."','$this->at64_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at64_codproj"]))
           $resac = db_query("insert into db_acount values($acount,1414,8359,'".AddSlashes(pg_result($resaco,$conresaco,'at64_codproj'))."','$this->at64_codproj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at64_codativ"]))
           $resac = db_query("insert into db_acount values($acount,1414,8360,'".AddSlashes(pg_result($resaco,$conresaco,'at64_codativ'))."','$this->at64_codativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at64_dtini"]))
           $resac = db_query("insert into db_acount values($acount,1414,9740,'".AddSlashes(pg_result($resaco,$conresaco,'at64_dtini'))."','$this->at64_dtini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at64_dtfim"]))
           $resac = db_query("insert into db_acount values($acount,1414,9741,'".AddSlashes(pg_result($resaco,$conresaco,'at64_dtfim'))."','$this->at64_dtfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at64_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1414,8352,'".AddSlashes(pg_result($resaco,$conresaco,'at64_situacao'))."','$this->at64_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at64_descricao"]))
           $resac = db_query("insert into db_acount values($acount,1414,11855,'".AddSlashes(pg_result($resaco,$conresaco,'at64_descricao'))."','$this->at64_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atividades dos Clientes nos projetos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at64_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atividades dos Clientes nos projetos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at64_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at64_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8358,'$at64_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1414,8358,'','".AddSlashes(pg_result($resaco,$iresaco,'at64_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1414,8359,'','".AddSlashes(pg_result($resaco,$iresaco,'at64_codproj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1414,8360,'','".AddSlashes(pg_result($resaco,$iresaco,'at64_codativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1414,9740,'','".AddSlashes(pg_result($resaco,$iresaco,'at64_dtini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1414,9741,'','".AddSlashes(pg_result($resaco,$iresaco,'at64_dtfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1414,8352,'','".AddSlashes(pg_result($resaco,$iresaco,'at64_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1414,11855,'','".AddSlashes(pg_result($resaco,$iresaco,'at64_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_projetosativcli
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at64_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at64_sequencial = $at64_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atividades dos Clientes nos projetos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at64_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atividades dos Clientes nos projetos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at64_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at64_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_projetosativcli";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $at64_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_projetosativcli ";
     $sql .= "      inner join db_projetoscliente  on  db_projetoscliente.at60_codproj = db_projetosativcli.at64_codproj";
     $sql .= "      inner join db_projetosituacao  on  db_projetosituacao.at61_codigo = db_projetosativcli.at64_situacao";
     $sql .= "      inner join db_projetosativid  on  db_projetosativid.at62_codigo = db_projetosativcli.at64_codativ";
     $sql .= "      inner join clientes  on  clientes.at01_codcli = db_projetoscliente.at60_codcli";
     $sql .= "      inner join db_syscadproced  on  db_syscadproced.codproced = db_projetoscliente.at60_codproced";
     $sql .= "      inner join db_sysmodulo  on  db_syscadproced.codmod = db_sysmodulo.codmod";
     $sql2 = "";
     if($dbwhere==""){
       if($at64_sequencial!=null ){
         $sql2 .= " where db_projetosativcli.at64_sequencial = $at64_sequencial "; 
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
   function sql_query_file ( $at64_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_projetosativcli ";
     $sql2 = "";
     if($dbwhere==""){
       if($at64_sequencial!=null ){
         $sql2 .= " where db_projetosativcli.at64_sequencial = $at64_sequencial "; 
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