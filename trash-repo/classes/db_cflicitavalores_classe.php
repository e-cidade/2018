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

//MODULO: licitacao
//CLASSE DA ENTIDADE cflicitavalores
class cl_cflicitavalores { 
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
   var $l40_sequencial = 0; 
   var $l40_codfclicita = 0; 
   var $l40_valorminimo = 0; 
   var $l40_valormaximo = 0; 
   var $l40_datainicial_dia = null; 
   var $l40_datainicial_mes = null; 
   var $l40_datainicial_ano = null; 
   var $l40_datainicial = null; 
   var $l40_datafinal_dia = null; 
   var $l40_datafinal_mes = null; 
   var $l40_datafinal_ano = null; 
   var $l40_datafinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l40_sequencial = int4 = Sequencial 
                 l40_codfclicita = int4 = Código da Licitação 
                 l40_valorminimo = float8 = Valor Minímo 
                 l40_valormaximo = float8 = Valor Máximo 
                 l40_datainicial = date = Data Inicial 
                 l40_datafinal = date = Data Final 
                 ";
   //funcao construtor da classe 
   function cl_cflicitavalores() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cflicitavalores"); 
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
       $this->l40_sequencial = ($this->l40_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l40_sequencial"]:$this->l40_sequencial);
       $this->l40_codfclicita = ($this->l40_codfclicita == ""?@$GLOBALS["HTTP_POST_VARS"]["l40_codfclicita"]:$this->l40_codfclicita);
       $this->l40_valorminimo = ($this->l40_valorminimo == ""?@$GLOBALS["HTTP_POST_VARS"]["l40_valorminimo"]:$this->l40_valorminimo);
       $this->l40_valormaximo = ($this->l40_valormaximo == ""?@$GLOBALS["HTTP_POST_VARS"]["l40_valormaximo"]:$this->l40_valormaximo);
       if($this->l40_datainicial == ""){
         $this->l40_datainicial_dia = ($this->l40_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l40_datainicial_dia"]:$this->l40_datainicial_dia);
         $this->l40_datainicial_mes = ($this->l40_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l40_datainicial_mes"]:$this->l40_datainicial_mes);
         $this->l40_datainicial_ano = ($this->l40_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l40_datainicial_ano"]:$this->l40_datainicial_ano);
         if($this->l40_datainicial_dia != ""){
            $this->l40_datainicial = $this->l40_datainicial_ano."-".$this->l40_datainicial_mes."-".$this->l40_datainicial_dia;
         }
       }
       if($this->l40_datafinal == ""){
         $this->l40_datafinal_dia = ($this->l40_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["l40_datafinal_dia"]:$this->l40_datafinal_dia);
         $this->l40_datafinal_mes = ($this->l40_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["l40_datafinal_mes"]:$this->l40_datafinal_mes);
         $this->l40_datafinal_ano = ($this->l40_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["l40_datafinal_ano"]:$this->l40_datafinal_ano);
         if($this->l40_datafinal_dia != ""){
            $this->l40_datafinal = $this->l40_datafinal_ano."-".$this->l40_datafinal_mes."-".$this->l40_datafinal_dia;
         }
       }
     }else{
       $this->l40_sequencial = ($this->l40_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l40_sequencial"]:$this->l40_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($l40_sequencial){ 
      $this->atualizacampos();
     if($this->l40_codfclicita == null ){ 
       $this->erro_sql = " Campo Código da Licitação nao Informado.";
       $this->erro_campo = "l40_codfclicita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l40_valorminimo == null ){ 
       $this->erro_sql = " Campo Valor Minímo nao Informado.";
       $this->erro_campo = "l40_valorminimo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l40_valormaximo == null ){ 
       $this->erro_sql = " Campo Valor Máximo nao Informado.";
       $this->erro_campo = "l40_valormaximo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l40_datainicial == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "l40_datainicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l40_datafinal == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "l40_datafinal_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l40_sequencial == "" || $l40_sequencial == null ){
       $result = db_query("select nextval('cflicitavalores_l40_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cflicitavalores_l40_sequencial_seq do campo: l40_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l40_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cflicitavalores_l40_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l40_sequencial)){
         $this->erro_sql = " Campo l40_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l40_sequencial = $l40_sequencial; 
       }
     }
     if(($this->l40_sequencial == null) || ($this->l40_sequencial == "") ){ 
       $this->erro_sql = " Campo l40_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cflicitavalores(
                                       l40_sequencial 
                                      ,l40_codfclicita 
                                      ,l40_valorminimo 
                                      ,l40_valormaximo 
                                      ,l40_datainicial 
                                      ,l40_datafinal 
                       )
                values (
                                $this->l40_sequencial 
                               ,$this->l40_codfclicita 
                               ,$this->l40_valorminimo 
                               ,$this->l40_valormaximo 
                               ,".($this->l40_datainicial == "null" || $this->l40_datainicial == ""?"null":"'".$this->l40_datainicial."'")." 
                               ,".($this->l40_datafinal == "null" || $this->l40_datafinal == ""?"null":"'".$this->l40_datafinal."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Faixa de Valores da Licitação ($this->l40_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Faixa de Valores da Licitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Faixa de Valores da Licitação ($this->l40_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l40_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->l40_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16672,'$this->l40_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2932,16672,'','".AddSlashes(pg_result($resaco,0,'l40_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2932,16673,'','".AddSlashes(pg_result($resaco,0,'l40_codfclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2932,16674,'','".AddSlashes(pg_result($resaco,0,'l40_valorminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2932,16675,'','".AddSlashes(pg_result($resaco,0,'l40_valormaximo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2932,16676,'','".AddSlashes(pg_result($resaco,0,'l40_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2932,16677,'','".AddSlashes(pg_result($resaco,0,'l40_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($l40_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cflicitavalores set ";
     $virgula = "";
     if(trim($this->l40_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l40_sequencial"])){ 
       $sql  .= $virgula." l40_sequencial = $this->l40_sequencial ";
       $virgula = ",";
       if(trim($this->l40_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "l40_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l40_codfclicita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l40_codfclicita"])){ 
       $sql  .= $virgula." l40_codfclicita = $this->l40_codfclicita ";
       $virgula = ",";
       if(trim($this->l40_codfclicita) == null ){ 
         $this->erro_sql = " Campo Código da Licitação nao Informado.";
         $this->erro_campo = "l40_codfclicita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l40_valorminimo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l40_valorminimo"])){ 
       $sql  .= $virgula." l40_valorminimo = $this->l40_valorminimo ";
       $virgula = ",";
       if(trim($this->l40_valorminimo) == null ){ 
         $this->erro_sql = " Campo Valor Minímo nao Informado.";
         $this->erro_campo = "l40_valorminimo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l40_valormaximo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l40_valormaximo"])){ 
       $sql  .= $virgula." l40_valormaximo = $this->l40_valormaximo ";
       $virgula = ",";
       if(trim($this->l40_valormaximo) == null ){ 
         $this->erro_sql = " Campo Valor Máximo nao Informado.";
         $this->erro_campo = "l40_valormaximo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l40_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l40_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l40_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." l40_datainicial = '$this->l40_datainicial' ";
       $virgula = ",";
       if(trim($this->l40_datainicial) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "l40_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["l40_datainicial_dia"])){ 
         $sql  .= $virgula." l40_datainicial = null ";
         $virgula = ",";
         if(trim($this->l40_datainicial) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "l40_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->l40_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l40_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["l40_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." l40_datafinal = '$this->l40_datafinal' ";
       $virgula = ",";
       if(trim($this->l40_datafinal) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "l40_datafinal_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["l40_datafinal_dia"])){ 
         $sql  .= $virgula." l40_datafinal = null ";
         $virgula = ",";
         if(trim($this->l40_datafinal) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "l40_datafinal_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($l40_sequencial!=null){
       $sql .= " l40_sequencial = $this->l40_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->l40_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16672,'$this->l40_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l40_sequencial"]) || $this->l40_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2932,16672,'".AddSlashes(pg_result($resaco,$conresaco,'l40_sequencial'))."','$this->l40_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l40_codfclicita"]) || $this->l40_codfclicita != "")
           $resac = db_query("insert into db_acount values($acount,2932,16673,'".AddSlashes(pg_result($resaco,$conresaco,'l40_codfclicita'))."','$this->l40_codfclicita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l40_valorminimo"]) || $this->l40_valorminimo != "")
           $resac = db_query("insert into db_acount values($acount,2932,16674,'".AddSlashes(pg_result($resaco,$conresaco,'l40_valorminimo'))."','$this->l40_valorminimo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l40_valormaximo"]) || $this->l40_valormaximo != "")
           $resac = db_query("insert into db_acount values($acount,2932,16675,'".AddSlashes(pg_result($resaco,$conresaco,'l40_valormaximo'))."','$this->l40_valormaximo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l40_datainicial"]) || $this->l40_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,2932,16676,'".AddSlashes(pg_result($resaco,$conresaco,'l40_datainicial'))."','$this->l40_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["l40_datafinal"]) || $this->l40_datafinal != "")
           $resac = db_query("insert into db_acount values($acount,2932,16677,'".AddSlashes(pg_result($resaco,$conresaco,'l40_datafinal'))."','$this->l40_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Faixa de Valores da Licitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l40_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Faixa de Valores da Licitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($l40_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($l40_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16672,'$l40_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2932,16672,'','".AddSlashes(pg_result($resaco,$iresaco,'l40_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2932,16673,'','".AddSlashes(pg_result($resaco,$iresaco,'l40_codfclicita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2932,16674,'','".AddSlashes(pg_result($resaco,$iresaco,'l40_valorminimo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2932,16675,'','".AddSlashes(pg_result($resaco,$iresaco,'l40_valormaximo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2932,16676,'','".AddSlashes(pg_result($resaco,$iresaco,'l40_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2932,16677,'','".AddSlashes(pg_result($resaco,$iresaco,'l40_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cflicitavalores
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($l40_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " l40_sequencial = $l40_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Faixa de Valores da Licitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l40_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Faixa de Valores da Licitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l40_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cflicitavalores";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $l40_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cflicitavalores ";
     $sql .= "      inner join cflicita  on  cflicita.l03_codigo = cflicitavalores.l40_codfclicita";
     $sql .= "      inner join db_config  on  db_config.codigo = cflicita.l03_instit";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = cflicita.l03_codcom";
     $sql2 = "";
     if($dbwhere==""){
       if($l40_sequencial!=null ){
         $sql2 .= " where cflicitavalores.l40_sequencial = $l40_sequencial "; 
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
   function sql_query_file ( $l40_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cflicitavalores ";
     $sql2 = "";
     if($dbwhere==""){
       if($l40_sequencial!=null ){
         $sql2 .= " where cflicitavalores.l40_sequencial = $l40_sequencial "; 
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