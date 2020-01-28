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

//MODULO: Farmacia
//CLASSE DA ENTIDADE far_arquivohiperdia
class cl_far_arquivohiperdia { 
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
   var $fa56_i_codigo = 0; 
   var $fa56_d_dataini_dia = null; 
   var $fa56_d_dataini_mes = null; 
   var $fa56_d_dataini_ano = null; 
   var $fa56_d_dataini = null; 
   var $fa56_d_datafim_dia = null; 
   var $fa56_d_datafim_mes = null; 
   var $fa56_d_datafim_ano = null; 
   var $fa56_d_datafim = null; 
   var $fa56_c_nomearquivo = null; 
   var $fa56_o_arquivo = 0; 
   var $fa56_i_login = 0; 
   var $fa56_d_datasistema_dia = null; 
   var $fa56_d_datasistema_mes = null; 
   var $fa56_d_datasistema_ano = null; 
   var $fa56_d_datasistema = null; 
   var $fa56_c_horasistema = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 fa56_i_codigo = int4 = Código 
                 fa56_d_dataini = date = Data de Início 
                 fa56_d_datafim = date = Data de Fim 
                 fa56_c_nomearquivo = varchar(50) = Nome do arquivo 
                 fa56_o_arquivo = oid = Arquivo 
                 fa56_i_login = int4 = Login 
                 fa56_d_datasistema = date = Data do sistema 
                 fa56_c_horasistema = char(5) = Hora do sistema 
                 ";
   //funcao construtor da classe 
   function cl_far_arquivohiperdia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("far_arquivohiperdia"); 
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
       $this->fa56_i_codigo = ($this->fa56_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_i_codigo"]:$this->fa56_i_codigo);
       if($this->fa56_d_dataini == ""){
         $this->fa56_d_dataini_dia = ($this->fa56_d_dataini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_d_dataini_dia"]:$this->fa56_d_dataini_dia);
         $this->fa56_d_dataini_mes = ($this->fa56_d_dataini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_d_dataini_mes"]:$this->fa56_d_dataini_mes);
         $this->fa56_d_dataini_ano = ($this->fa56_d_dataini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_d_dataini_ano"]:$this->fa56_d_dataini_ano);
         if($this->fa56_d_dataini_dia != ""){
            $this->fa56_d_dataini = $this->fa56_d_dataini_ano."-".$this->fa56_d_dataini_mes."-".$this->fa56_d_dataini_dia;
         }
       }
       if($this->fa56_d_datafim == ""){
         $this->fa56_d_datafim_dia = ($this->fa56_d_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_d_datafim_dia"]:$this->fa56_d_datafim_dia);
         $this->fa56_d_datafim_mes = ($this->fa56_d_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_d_datafim_mes"]:$this->fa56_d_datafim_mes);
         $this->fa56_d_datafim_ano = ($this->fa56_d_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_d_datafim_ano"]:$this->fa56_d_datafim_ano);
         if($this->fa56_d_datafim_dia != ""){
            $this->fa56_d_datafim = $this->fa56_d_datafim_ano."-".$this->fa56_d_datafim_mes."-".$this->fa56_d_datafim_dia;
         }
       }
       $this->fa56_c_nomearquivo = ($this->fa56_c_nomearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_c_nomearquivo"]:$this->fa56_c_nomearquivo);
       $this->fa56_o_arquivo = ($this->fa56_o_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_o_arquivo"]:$this->fa56_o_arquivo);
       $this->fa56_i_login = ($this->fa56_i_login == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_i_login"]:$this->fa56_i_login);
       if($this->fa56_d_datasistema == ""){
         $this->fa56_d_datasistema_dia = ($this->fa56_d_datasistema_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_d_datasistema_dia"]:$this->fa56_d_datasistema_dia);
         $this->fa56_d_datasistema_mes = ($this->fa56_d_datasistema_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_d_datasistema_mes"]:$this->fa56_d_datasistema_mes);
         $this->fa56_d_datasistema_ano = ($this->fa56_d_datasistema_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_d_datasistema_ano"]:$this->fa56_d_datasistema_ano);
         if($this->fa56_d_datasistema_dia != ""){
            $this->fa56_d_datasistema = $this->fa56_d_datasistema_ano."-".$this->fa56_d_datasistema_mes."-".$this->fa56_d_datasistema_dia;
         }
       }
       $this->fa56_c_horasistema = ($this->fa56_c_horasistema == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_c_horasistema"]:$this->fa56_c_horasistema);
     }else{
       $this->fa56_i_codigo = ($this->fa56_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["fa56_i_codigo"]:$this->fa56_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($fa56_i_codigo){ 
      $this->atualizacampos();
     if($this->fa56_d_dataini == null ){ 
       $this->erro_sql = " Campo Data de Início nao Informado.";
       $this->erro_campo = "fa56_d_dataini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa56_d_datafim == null ){ 
       $this->erro_sql = " Campo Data de Fim nao Informado.";
       $this->erro_campo = "fa56_d_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa56_c_nomearquivo == null ){ 
       $this->erro_sql = " Campo Nome do arquivo nao Informado.";
       $this->erro_campo = "fa56_c_nomearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa56_o_arquivo == null ){ 
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "fa56_o_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa56_i_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "fa56_i_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa56_d_datasistema == null ){ 
       $this->erro_sql = " Campo Data do sistema nao Informado.";
       $this->erro_campo = "fa56_d_datasistema_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->fa56_c_horasistema == null ){ 
       $this->erro_sql = " Campo Hora do sistema nao Informado.";
       $this->erro_campo = "fa56_c_horasistema";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($fa56_i_codigo == "" || $fa56_i_codigo == null ){
       $result = db_query("select nextval('far_arquivohiperdia_fa56_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: far_arquivohiperdia_fa56_i_codigo_seq do campo: fa56_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->fa56_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from far_arquivohiperdia_fa56_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $fa56_i_codigo)){
         $this->erro_sql = " Campo fa56_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->fa56_i_codigo = $fa56_i_codigo; 
       }
     }
     if(($this->fa56_i_codigo == null) || ($this->fa56_i_codigo == "") ){ 
       $this->erro_sql = " Campo fa56_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into far_arquivohiperdia(
                                       fa56_i_codigo 
                                      ,fa56_d_dataini 
                                      ,fa56_d_datafim 
                                      ,fa56_c_nomearquivo 
                                      ,fa56_o_arquivo 
                                      ,fa56_i_login 
                                      ,fa56_d_datasistema 
                                      ,fa56_c_horasistema 
                       )
                values (
                                $this->fa56_i_codigo 
                               ,".($this->fa56_d_dataini == "null" || $this->fa56_d_dataini == ""?"null":"'".$this->fa56_d_dataini."'")." 
                               ,".($this->fa56_d_datafim == "null" || $this->fa56_d_datafim == ""?"null":"'".$this->fa56_d_datafim."'")." 
                               ,'$this->fa56_c_nomearquivo' 
                               ,$this->fa56_o_arquivo 
                               ,$this->fa56_i_login 
                               ,".($this->fa56_d_datasistema == "null" || $this->fa56_d_datasistema == ""?"null":"'".$this->fa56_d_datasistema."'")." 
                               ,'$this->fa56_c_horasistema' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "far_arquivohiperdia ($this->fa56_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "far_arquivohiperdia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "far_arquivohiperdia ($this->fa56_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa56_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->fa56_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17420,'$this->fa56_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3081,17420,'','".AddSlashes(pg_result($resaco,0,'fa56_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3081,17421,'','".AddSlashes(pg_result($resaco,0,'fa56_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3081,17422,'','".AddSlashes(pg_result($resaco,0,'fa56_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3081,17426,'','".AddSlashes(pg_result($resaco,0,'fa56_c_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3081,17427,'','".AddSlashes(pg_result($resaco,0,'fa56_o_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3081,17423,'','".AddSlashes(pg_result($resaco,0,'fa56_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3081,17424,'','".AddSlashes(pg_result($resaco,0,'fa56_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3081,17425,'','".AddSlashes(pg_result($resaco,0,'fa56_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($fa56_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update far_arquivohiperdia set ";
     $virgula = "";
     if(trim($this->fa56_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa56_i_codigo"])){ 
       $sql  .= $virgula." fa56_i_codigo = $this->fa56_i_codigo ";
       $virgula = ",";
       if(trim($this->fa56_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "fa56_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa56_d_dataini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa56_d_dataini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa56_d_dataini_dia"] !="") ){ 
       $sql  .= $virgula." fa56_d_dataini = '$this->fa56_d_dataini' ";
       $virgula = ",";
       if(trim($this->fa56_d_dataini) == null ){ 
         $this->erro_sql = " Campo Data de Início nao Informado.";
         $this->erro_campo = "fa56_d_dataini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa56_d_dataini_dia"])){ 
         $sql  .= $virgula." fa56_d_dataini = null ";
         $virgula = ",";
         if(trim($this->fa56_d_dataini) == null ){ 
           $this->erro_sql = " Campo Data de Início nao Informado.";
           $this->erro_campo = "fa56_d_dataini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa56_d_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa56_d_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa56_d_datafim_dia"] !="") ){ 
       $sql  .= $virgula." fa56_d_datafim = '$this->fa56_d_datafim' ";
       $virgula = ",";
       if(trim($this->fa56_d_datafim) == null ){ 
         $this->erro_sql = " Campo Data de Fim nao Informado.";
         $this->erro_campo = "fa56_d_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa56_d_datafim_dia"])){ 
         $sql  .= $virgula." fa56_d_datafim = null ";
         $virgula = ",";
         if(trim($this->fa56_d_datafim) == null ){ 
           $this->erro_sql = " Campo Data de Fim nao Informado.";
           $this->erro_campo = "fa56_d_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa56_c_nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa56_c_nomearquivo"])){ 
       $sql  .= $virgula." fa56_c_nomearquivo = '$this->fa56_c_nomearquivo' ";
       $virgula = ",";
       if(trim($this->fa56_c_nomearquivo) == null ){ 
         $this->erro_sql = " Campo Nome do arquivo nao Informado.";
         $this->erro_campo = "fa56_c_nomearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa56_o_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa56_o_arquivo"])){ 
       $sql  .= $virgula." fa56_o_arquivo = $this->fa56_o_arquivo ";
       $virgula = ",";
       if(trim($this->fa56_o_arquivo) == null ){ 
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "fa56_o_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa56_i_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa56_i_login"])){ 
       $sql  .= $virgula." fa56_i_login = $this->fa56_i_login ";
       $virgula = ",";
       if(trim($this->fa56_i_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "fa56_i_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->fa56_d_datasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa56_d_datasistema_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["fa56_d_datasistema_dia"] !="") ){ 
       $sql  .= $virgula." fa56_d_datasistema = '$this->fa56_d_datasistema' ";
       $virgula = ",";
       if(trim($this->fa56_d_datasistema) == null ){ 
         $this->erro_sql = " Campo Data do sistema nao Informado.";
         $this->erro_campo = "fa56_d_datasistema_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["fa56_d_datasistema_dia"])){ 
         $sql  .= $virgula." fa56_d_datasistema = null ";
         $virgula = ",";
         if(trim($this->fa56_d_datasistema) == null ){ 
           $this->erro_sql = " Campo Data do sistema nao Informado.";
           $this->erro_campo = "fa56_d_datasistema_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->fa56_c_horasistema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["fa56_c_horasistema"])){ 
       $sql  .= $virgula." fa56_c_horasistema = '$this->fa56_c_horasistema' ";
       $virgula = ",";
       if(trim($this->fa56_c_horasistema) == null ){ 
         $this->erro_sql = " Campo Hora do sistema nao Informado.";
         $this->erro_campo = "fa56_c_horasistema";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($fa56_i_codigo!=null){
       $sql .= " fa56_i_codigo = $this->fa56_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->fa56_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17420,'$this->fa56_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa56_i_codigo"]) || $this->fa56_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3081,17420,'".AddSlashes(pg_result($resaco,$conresaco,'fa56_i_codigo'))."','$this->fa56_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa56_d_dataini"]) || $this->fa56_d_dataini != "")
           $resac = db_query("insert into db_acount values($acount,3081,17421,'".AddSlashes(pg_result($resaco,$conresaco,'fa56_d_dataini'))."','$this->fa56_d_dataini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa56_d_datafim"]) || $this->fa56_d_datafim != "")
           $resac = db_query("insert into db_acount values($acount,3081,17422,'".AddSlashes(pg_result($resaco,$conresaco,'fa56_d_datafim'))."','$this->fa56_d_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa56_c_nomearquivo"]) || $this->fa56_c_nomearquivo != "")
           $resac = db_query("insert into db_acount values($acount,3081,17426,'".AddSlashes(pg_result($resaco,$conresaco,'fa56_c_nomearquivo'))."','$this->fa56_c_nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa56_o_arquivo"]) || $this->fa56_o_arquivo != "")
           $resac = db_query("insert into db_acount values($acount,3081,17427,'".AddSlashes(pg_result($resaco,$conresaco,'fa56_o_arquivo'))."','$this->fa56_o_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa56_i_login"]) || $this->fa56_i_login != "")
           $resac = db_query("insert into db_acount values($acount,3081,17423,'".AddSlashes(pg_result($resaco,$conresaco,'fa56_i_login'))."','$this->fa56_i_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa56_d_datasistema"]) || $this->fa56_d_datasistema != "")
           $resac = db_query("insert into db_acount values($acount,3081,17424,'".AddSlashes(pg_result($resaco,$conresaco,'fa56_d_datasistema'))."','$this->fa56_d_datasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["fa56_c_horasistema"]) || $this->fa56_c_horasistema != "")
           $resac = db_query("insert into db_acount values($acount,3081,17425,'".AddSlashes(pg_result($resaco,$conresaco,'fa56_c_horasistema'))."','$this->fa56_c_horasistema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_arquivohiperdia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa56_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_arquivohiperdia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->fa56_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->fa56_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($fa56_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($fa56_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17420,'$fa56_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3081,17420,'','".AddSlashes(pg_result($resaco,$iresaco,'fa56_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3081,17421,'','".AddSlashes(pg_result($resaco,$iresaco,'fa56_d_dataini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3081,17422,'','".AddSlashes(pg_result($resaco,$iresaco,'fa56_d_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3081,17426,'','".AddSlashes(pg_result($resaco,$iresaco,'fa56_c_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3081,17427,'','".AddSlashes(pg_result($resaco,$iresaco,'fa56_o_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3081,17423,'','".AddSlashes(pg_result($resaco,$iresaco,'fa56_i_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3081,17424,'','".AddSlashes(pg_result($resaco,$iresaco,'fa56_d_datasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3081,17425,'','".AddSlashes(pg_result($resaco,$iresaco,'fa56_c_horasistema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from far_arquivohiperdia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($fa56_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " fa56_i_codigo = $fa56_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "far_arquivohiperdia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$fa56_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "far_arquivohiperdia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$fa56_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$fa56_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:far_arquivohiperdia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $fa56_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_arquivohiperdia ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = far_arquivohiperdia.fa56_i_login";
     $sql2 = "";
     if($dbwhere==""){
       if($fa56_i_codigo!=null ){
         $sql2 .= " where far_arquivohiperdia.fa56_i_codigo = $fa56_i_codigo "; 
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
   function sql_query_file ( $fa56_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from far_arquivohiperdia ";
     $sql2 = "";
     if($dbwhere==""){
       if($fa56_i_codigo!=null ){
         $sql2 .= " where far_arquivohiperdia.fa56_i_codigo = $fa56_i_codigo "; 
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