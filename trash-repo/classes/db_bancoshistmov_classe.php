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

//MODULO: caixa
//CLASSE DA ENTIDADE bancoshistmov
class cl_bancoshistmov { 
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
   var $k66_sequencial = 0; 
   var $k66_codbco = 0; 
   var $k66_bancoshistmovcategoria = 0; 
   var $k66_historico = 0; 
   var $k66_descricao = null; 
   var $k66_vigencia_dia = null; 
   var $k66_vigencia_mes = null; 
   var $k66_vigencia_ano = null; 
   var $k66_vigencia = null; 
   var $k66_sigla = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k66_sequencial = int4 = Codigo sequencial 
                 k66_codbco = int4 = codigo do banco 
                 k66_bancoshistmovcategoria = int4 = Codigo da categoria do movimento 
                 k66_historico = int4 = Codigo do movimento no banco 
                 k66_descricao = varchar(255) = Descrição da categoria 
                 k66_vigencia = date = Vigencia 
                 k66_sigla = char(3) = Sigla 
                 ";
   //funcao construtor da classe 
   function cl_bancoshistmov() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("bancoshistmov"); 
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
       $this->k66_sequencial = ($this->k66_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k66_sequencial"]:$this->k66_sequencial);
       $this->k66_codbco = ($this->k66_codbco == ""?@$GLOBALS["HTTP_POST_VARS"]["k66_codbco"]:$this->k66_codbco);
       $this->k66_bancoshistmovcategoria = ($this->k66_bancoshistmovcategoria == ""?@$GLOBALS["HTTP_POST_VARS"]["k66_bancoshistmovcategoria"]:$this->k66_bancoshistmovcategoria);
       $this->k66_historico = ($this->k66_historico == ""?@$GLOBALS["HTTP_POST_VARS"]["k66_historico"]:$this->k66_historico);
       $this->k66_descricao = ($this->k66_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["k66_descricao"]:$this->k66_descricao);
       if($this->k66_vigencia == ""){
         $this->k66_vigencia_dia = ($this->k66_vigencia_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k66_vigencia_dia"]:$this->k66_vigencia_dia);
         $this->k66_vigencia_mes = ($this->k66_vigencia_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k66_vigencia_mes"]:$this->k66_vigencia_mes);
         $this->k66_vigencia_ano = ($this->k66_vigencia_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k66_vigencia_ano"]:$this->k66_vigencia_ano);
         if($this->k66_vigencia_dia != ""){
            $this->k66_vigencia = $this->k66_vigencia_ano."-".$this->k66_vigencia_mes."-".$this->k66_vigencia_dia;
         }
       }
       $this->k66_sigla = ($this->k66_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["k66_sigla"]:$this->k66_sigla);
     }else{
       $this->k66_sequencial = ($this->k66_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k66_sequencial"]:$this->k66_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k66_sequencial){ 
      $this->atualizacampos();
     if($this->k66_codbco == null ){ 
       $this->erro_sql = " Campo codigo do banco nao Informado.";
       $this->erro_campo = "k66_codbco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k66_bancoshistmovcategoria == null ){ 
       $this->erro_sql = " Campo Codigo da categoria do movimento nao Informado.";
       $this->erro_campo = "k66_bancoshistmovcategoria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k66_historico == null ){ 
       $this->erro_sql = " Campo Codigo do movimento no banco nao Informado.";
       $this->erro_campo = "k66_historico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k66_descricao == null ){ 
       $this->erro_sql = " Campo Descrição da categoria nao Informado.";
       $this->erro_campo = "k66_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k66_vigencia == null ){ 
       $this->erro_sql = " Campo Vigencia nao Informado.";
       $this->erro_campo = "k66_vigencia_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k66_sigla == null ){ 
       $this->erro_sql = " Campo Sigla nao Informado.";
       $this->erro_campo = "k66_sigla";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k66_sequencial == "" || $k66_sequencial == null ){
       $result = db_query("select nextval('bancoshistmov_k66_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: bancoshistmov_k66_sequencial_seq do campo: k66_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k66_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from bancoshistmov_k66_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k66_sequencial)){
         $this->erro_sql = " Campo k66_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k66_sequencial = $k66_sequencial; 
       }
     }
     if(($this->k66_sequencial == null) || ($this->k66_sequencial == "") ){ 
       $this->erro_sql = " Campo k66_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into bancoshistmov(
                                       k66_sequencial 
                                      ,k66_codbco 
                                      ,k66_bancoshistmovcategoria 
                                      ,k66_historico 
                                      ,k66_descricao 
                                      ,k66_vigencia 
                                      ,k66_sigla 
                       )
                values (
                                $this->k66_sequencial 
                               ,$this->k66_codbco 
                               ,$this->k66_bancoshistmovcategoria 
                               ,$this->k66_historico 
                               ,'$this->k66_descricao' 
                               ,".($this->k66_vigencia == "null" || $this->k66_vigencia == ""?"null":"'".$this->k66_vigencia."'")." 
                               ,'$this->k66_sigla' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de movimento bancario ($this->k66_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de movimento bancario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de movimento bancario ($this->k66_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k66_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k66_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10044,'$this->k66_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1727,10044,'','".AddSlashes(pg_result($resaco,0,'k66_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1727,10045,'','".AddSlashes(pg_result($resaco,0,'k66_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1727,10046,'','".AddSlashes(pg_result($resaco,0,'k66_bancoshistmovcategoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1727,10047,'','".AddSlashes(pg_result($resaco,0,'k66_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1727,10048,'','".AddSlashes(pg_result($resaco,0,'k66_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1727,10049,'','".AddSlashes(pg_result($resaco,0,'k66_vigencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1727,10050,'','".AddSlashes(pg_result($resaco,0,'k66_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k66_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update bancoshistmov set ";
     $virgula = "";
     if(trim($this->k66_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k66_sequencial"])){ 
       $sql  .= $virgula." k66_sequencial = $this->k66_sequencial ";
       $virgula = ",";
       if(trim($this->k66_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "k66_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k66_codbco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k66_codbco"])){ 
       $sql  .= $virgula." k66_codbco = $this->k66_codbco ";
       $virgula = ",";
       if(trim($this->k66_codbco) == null ){ 
         $this->erro_sql = " Campo codigo do banco nao Informado.";
         $this->erro_campo = "k66_codbco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k66_bancoshistmovcategoria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k66_bancoshistmovcategoria"])){ 
       $sql  .= $virgula." k66_bancoshistmovcategoria = $this->k66_bancoshistmovcategoria ";
       $virgula = ",";
       if(trim($this->k66_bancoshistmovcategoria) == null ){ 
         $this->erro_sql = " Campo Codigo da categoria do movimento nao Informado.";
         $this->erro_campo = "k66_bancoshistmovcategoria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k66_historico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k66_historico"])){ 
       $sql  .= $virgula." k66_historico = $this->k66_historico ";
       $virgula = ",";
       if(trim($this->k66_historico) == null ){ 
         $this->erro_sql = " Campo Codigo do movimento no banco nao Informado.";
         $this->erro_campo = "k66_historico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k66_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k66_descricao"])){ 
       $sql  .= $virgula." k66_descricao = '$this->k66_descricao' ";
       $virgula = ",";
       if(trim($this->k66_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição da categoria nao Informado.";
         $this->erro_campo = "k66_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k66_vigencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k66_vigencia_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k66_vigencia_dia"] !="") ){ 
       $sql  .= $virgula." k66_vigencia = '$this->k66_vigencia' ";
       $virgula = ",";
       if(trim($this->k66_vigencia) == null ){ 
         $this->erro_sql = " Campo Vigencia nao Informado.";
         $this->erro_campo = "k66_vigencia_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k66_vigencia_dia"])){ 
         $sql  .= $virgula." k66_vigencia = null ";
         $virgula = ",";
         if(trim($this->k66_vigencia) == null ){ 
           $this->erro_sql = " Campo Vigencia nao Informado.";
           $this->erro_campo = "k66_vigencia_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k66_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k66_sigla"])){ 
       $sql  .= $virgula." k66_sigla = '$this->k66_sigla' ";
       $virgula = ",";
       if(trim($this->k66_sigla) == null ){ 
         $this->erro_sql = " Campo Sigla nao Informado.";
         $this->erro_campo = "k66_sigla";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k66_sequencial!=null){
       $sql .= " k66_sequencial = $this->k66_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k66_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10044,'$this->k66_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k66_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1727,10044,'".AddSlashes(pg_result($resaco,$conresaco,'k66_sequencial'))."','$this->k66_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k66_codbco"]))
           $resac = db_query("insert into db_acount values($acount,1727,10045,'".AddSlashes(pg_result($resaco,$conresaco,'k66_codbco'))."','$this->k66_codbco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k66_bancoshistmovcategoria"]))
           $resac = db_query("insert into db_acount values($acount,1727,10046,'".AddSlashes(pg_result($resaco,$conresaco,'k66_bancoshistmovcategoria'))."','$this->k66_bancoshistmovcategoria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k66_historico"]))
           $resac = db_query("insert into db_acount values($acount,1727,10047,'".AddSlashes(pg_result($resaco,$conresaco,'k66_historico'))."','$this->k66_historico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k66_descricao"]))
           $resac = db_query("insert into db_acount values($acount,1727,10048,'".AddSlashes(pg_result($resaco,$conresaco,'k66_descricao'))."','$this->k66_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k66_vigencia"]))
           $resac = db_query("insert into db_acount values($acount,1727,10049,'".AddSlashes(pg_result($resaco,$conresaco,'k66_vigencia'))."','$this->k66_vigencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k66_sigla"]))
           $resac = db_query("insert into db_acount values($acount,1727,10050,'".AddSlashes(pg_result($resaco,$conresaco,'k66_sigla'))."','$this->k66_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de movimento bancario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k66_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de movimento bancario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k66_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k66_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10044,'$k66_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1727,10044,'','".AddSlashes(pg_result($resaco,$iresaco,'k66_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1727,10045,'','".AddSlashes(pg_result($resaco,$iresaco,'k66_codbco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1727,10046,'','".AddSlashes(pg_result($resaco,$iresaco,'k66_bancoshistmovcategoria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1727,10047,'','".AddSlashes(pg_result($resaco,$iresaco,'k66_historico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1727,10048,'','".AddSlashes(pg_result($resaco,$iresaco,'k66_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1727,10049,'','".AddSlashes(pg_result($resaco,$iresaco,'k66_vigencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1727,10050,'','".AddSlashes(pg_result($resaco,$iresaco,'k66_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from bancoshistmov
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k66_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k66_sequencial = $k66_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de movimento bancario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k66_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de movimento bancario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k66_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k66_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:bancoshistmov";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $k66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bancoshistmov ";
     $sql .= "      inner join bancos  on  bancos.codbco = bancoshistmov.k66_codbco";
     $sql .= "      inner join bancoshistmovcategoria  on  bancoshistmovcategoria.k67_sequencial = bancoshistmov.k66_bancoshistmovcategoria";
     $sql2 = "";
     if($dbwhere==""){
       if($k66_sequencial!=null ){
         $sql2 .= " where bancoshistmov.k66_sequencial = $k66_sequencial "; 
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
   function sql_query_file ( $k66_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from bancoshistmov ";
     $sql2 = "";
     if($dbwhere==""){
       if($k66_sequencial!=null ){
         $sql2 .= " where bancoshistmov.k66_sequencial = $k66_sequencial "; 
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