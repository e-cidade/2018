<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: acordos
//CLASSE DA ENTIDADE acordocomissao
class cl_acordocomissao { 
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
   var $ac08_sequencial = 0; 
   var $ac08_descricao = null; 
   var $ac08_observacao = null; 
   var $ac08_datainicial_dia = null; 
   var $ac08_datainicial_mes = null; 
   var $ac08_datainicial_ano = null; 
   var $ac08_datainicial = null; 
   var $ac08_datafim_dia = null; 
   var $ac08_datafim_mes = null; 
   var $ac08_datafim_ano = null; 
   var $ac08_datafim = null; 
   var $ac08_instit = 0; 
   var $ac08_acordocomissaotipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac08_sequencial = int4 = Sequencial 
                 ac08_descricao = varchar(100) = Descrição 
                 ac08_observacao = text = Observação 
                 ac08_datainicial = date = Data Inicial 
                 ac08_datafim = date = Data Final 
                 ac08_instit = int4 = Instituição 
                 ac08_acordocomissaotipo = int4 = Comissão 
                 ";
   //funcao construtor da classe 
   function cl_acordocomissao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordocomissao"); 
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
       $this->ac08_sequencial = ($this->ac08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac08_sequencial"]:$this->ac08_sequencial);
       $this->ac08_descricao = ($this->ac08_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac08_descricao"]:$this->ac08_descricao);
       $this->ac08_observacao = ($this->ac08_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac08_observacao"]:$this->ac08_observacao);
       if($this->ac08_datainicial == ""){
         $this->ac08_datainicial_dia = ($this->ac08_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac08_datainicial_dia"]:$this->ac08_datainicial_dia);
         $this->ac08_datainicial_mes = ($this->ac08_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac08_datainicial_mes"]:$this->ac08_datainicial_mes);
         $this->ac08_datainicial_ano = ($this->ac08_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac08_datainicial_ano"]:$this->ac08_datainicial_ano);
         if($this->ac08_datainicial_dia != ""){
            $this->ac08_datainicial = $this->ac08_datainicial_ano."-".$this->ac08_datainicial_mes."-".$this->ac08_datainicial_dia;
         }
       }
       if($this->ac08_datafim == ""){
         $this->ac08_datafim_dia = ($this->ac08_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac08_datafim_dia"]:$this->ac08_datafim_dia);
         $this->ac08_datafim_mes = ($this->ac08_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac08_datafim_mes"]:$this->ac08_datafim_mes);
         $this->ac08_datafim_ano = ($this->ac08_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac08_datafim_ano"]:$this->ac08_datafim_ano);
         if($this->ac08_datafim_dia != ""){
            $this->ac08_datafim = $this->ac08_datafim_ano."-".$this->ac08_datafim_mes."-".$this->ac08_datafim_dia;
         }
       }
       $this->ac08_instit = ($this->ac08_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["ac08_instit"]:$this->ac08_instit);
       $this->ac08_acordocomissaotipo = ($this->ac08_acordocomissaotipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ac08_acordocomissaotipo"]:$this->ac08_acordocomissaotipo);
     }else{
       $this->ac08_sequencial = ($this->ac08_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac08_sequencial"]:$this->ac08_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac08_sequencial){ 
      $this->atualizacampos();
     if($this->ac08_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ac08_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac08_observacao == null ){ 
       $this->erro_sql = " Campo Observação nao Informado.";
       $this->erro_campo = "ac08_observacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac08_datainicial == null ){ 
       $this->erro_sql = " Campo Data Inicial nao Informado.";
       $this->erro_campo = "ac08_datainicial_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac08_datafim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "ac08_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac08_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "ac08_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac08_acordocomissaotipo == null ){ 
       $this->erro_sql = " Campo Comissão nao Informado.";
       $this->erro_campo = "ac08_acordocomissaotipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac08_sequencial == "" || $ac08_sequencial == null ){
       $result = db_query("select nextval('acordocomissao_ac08_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordocomissao_ac08_sequencial_seq do campo: ac08_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac08_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordocomissao_ac08_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac08_sequencial)){
         $this->erro_sql = " Campo ac08_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac08_sequencial = $ac08_sequencial; 
       }
     }
     if(($this->ac08_sequencial == null) || ($this->ac08_sequencial == "") ){ 
       $this->erro_sql = " Campo ac08_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordocomissao(
                                       ac08_sequencial 
                                      ,ac08_descricao 
                                      ,ac08_observacao 
                                      ,ac08_datainicial 
                                      ,ac08_datafim 
                                      ,ac08_instit 
                                      ,ac08_acordocomissaotipo 
                       )
                values (
                                $this->ac08_sequencial 
                               ,'$this->ac08_descricao' 
                               ,'$this->ac08_observacao' 
                               ,".($this->ac08_datainicial == "null" || $this->ac08_datainicial == ""?"null":"'".$this->ac08_datainicial."'")." 
                               ,".($this->ac08_datafim == "null" || $this->ac08_datafim == ""?"null":"'".$this->ac08_datafim."'")." 
                               ,$this->ac08_instit 
                               ,$this->ac08_acordocomissaotipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Comissão ($this->ac08_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Comissão já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Comissão ($this->ac08_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac08_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac08_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16136,'$this->ac08_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2830,16136,'','".AddSlashes(pg_result($resaco,0,'ac08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2830,16137,'','".AddSlashes(pg_result($resaco,0,'ac08_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2830,16138,'','".AddSlashes(pg_result($resaco,0,'ac08_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2830,16139,'','".AddSlashes(pg_result($resaco,0,'ac08_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2830,16140,'','".AddSlashes(pg_result($resaco,0,'ac08_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2830,19312,'','".AddSlashes(pg_result($resaco,0,'ac08_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2830,19315,'','".AddSlashes(pg_result($resaco,0,'ac08_acordocomissaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac08_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordocomissao set ";
     $virgula = "";
     if(trim($this->ac08_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac08_sequencial"])){ 
       $sql  .= $virgula." ac08_sequencial = $this->ac08_sequencial ";
       $virgula = ",";
       if(trim($this->ac08_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac08_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac08_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac08_descricao"])){ 
       $sql  .= $virgula." ac08_descricao = '$this->ac08_descricao' ";
       $virgula = ",";
       if(trim($this->ac08_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ac08_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac08_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac08_observacao"])){ 
       $sql  .= $virgula." ac08_observacao = '$this->ac08_observacao' ";
       $virgula = ",";
       if(trim($this->ac08_observacao) == null ){ 
         $this->erro_sql = " Campo Observação nao Informado.";
         $this->erro_campo = "ac08_observacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac08_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac08_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac08_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." ac08_datainicial = '$this->ac08_datainicial' ";
       $virgula = ",";
       if(trim($this->ac08_datainicial) == null ){ 
         $this->erro_sql = " Campo Data Inicial nao Informado.";
         $this->erro_campo = "ac08_datainicial_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac08_datainicial_dia"])){ 
         $sql  .= $virgula." ac08_datainicial = null ";
         $virgula = ",";
         if(trim($this->ac08_datainicial) == null ){ 
           $this->erro_sql = " Campo Data Inicial nao Informado.";
           $this->erro_campo = "ac08_datainicial_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac08_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac08_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac08_datafim_dia"] !="") ){ 
       $sql  .= $virgula." ac08_datafim = '$this->ac08_datafim' ";
       $virgula = ",";
       if(trim($this->ac08_datafim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "ac08_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac08_datafim_dia"])){ 
         $sql  .= $virgula." ac08_datafim = null ";
         $virgula = ",";
         if(trim($this->ac08_datafim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "ac08_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac08_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac08_instit"])){ 
       $sql  .= $virgula." ac08_instit = $this->ac08_instit ";
       $virgula = ",";
       if(trim($this->ac08_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "ac08_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac08_acordocomissaotipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac08_acordocomissaotipo"])){ 
       $sql  .= $virgula." ac08_acordocomissaotipo = $this->ac08_acordocomissaotipo ";
       $virgula = ",";
       if(trim($this->ac08_acordocomissaotipo) == null ){ 
         $this->erro_sql = " Campo Comissão nao Informado.";
         $this->erro_campo = "ac08_acordocomissaotipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac08_sequencial!=null){
       $sql .= " ac08_sequencial = $this->ac08_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac08_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16136,'$this->ac08_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac08_sequencial"]) || $this->ac08_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2830,16136,'".AddSlashes(pg_result($resaco,$conresaco,'ac08_sequencial'))."','$this->ac08_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac08_descricao"]) || $this->ac08_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2830,16137,'".AddSlashes(pg_result($resaco,$conresaco,'ac08_descricao'))."','$this->ac08_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac08_observacao"]) || $this->ac08_observacao != "")
           $resac = db_query("insert into db_acount values($acount,2830,16138,'".AddSlashes(pg_result($resaco,$conresaco,'ac08_observacao'))."','$this->ac08_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac08_datainicial"]) || $this->ac08_datainicial != "")
           $resac = db_query("insert into db_acount values($acount,2830,16139,'".AddSlashes(pg_result($resaco,$conresaco,'ac08_datainicial'))."','$this->ac08_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac08_datafim"]) || $this->ac08_datafim != "")
           $resac = db_query("insert into db_acount values($acount,2830,16140,'".AddSlashes(pg_result($resaco,$conresaco,'ac08_datafim'))."','$this->ac08_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac08_instit"]) || $this->ac08_instit != "")
           $resac = db_query("insert into db_acount values($acount,2830,19312,'".AddSlashes(pg_result($resaco,$conresaco,'ac08_instit'))."','$this->ac08_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac08_acordocomissaotipo"]) || $this->ac08_acordocomissaotipo != "")
           $resac = db_query("insert into db_acount values($acount,2830,19315,'".AddSlashes(pg_result($resaco,$conresaco,'ac08_acordocomissaotipo'))."','$this->ac08_acordocomissaotipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Comissão nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac08_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Comissão nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac08_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac08_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16136,'$ac08_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2830,16136,'','".AddSlashes(pg_result($resaco,$iresaco,'ac08_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2830,16137,'','".AddSlashes(pg_result($resaco,$iresaco,'ac08_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2830,16138,'','".AddSlashes(pg_result($resaco,$iresaco,'ac08_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2830,16139,'','".AddSlashes(pg_result($resaco,$iresaco,'ac08_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2830,16140,'','".AddSlashes(pg_result($resaco,$iresaco,'ac08_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2830,19312,'','".AddSlashes(pg_result($resaco,$iresaco,'ac08_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2830,19315,'','".AddSlashes(pg_result($resaco,$iresaco,'ac08_acordocomissaotipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordocomissao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac08_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac08_sequencial = $ac08_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Comissão nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac08_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Comissão nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac08_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac08_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordocomissao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac08_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordocomissao ";
     $sql .= "      inner join db_config  on  db_config.codigo = acordocomissao.ac08_instit";
     $sql .= "      inner join acordocomissaotipo  on  acordocomissaotipo.ac43_sequencial = acordocomissao.ac08_acordocomissaotipo";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($ac08_sequencial!=null ){
         $sql2 .= " where acordocomissao.ac08_sequencial = $ac08_sequencial "; 
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
   function sql_query_file ( $ac08_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordocomissao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac08_sequencial!=null ){
         $sql2 .= " where acordocomissao.ac08_sequencial = $ac08_sequencial "; 
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