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

//MODULO: Cemiterio
//CLASSE DA ENTIDADE itenserv
class cl_itenserv { 
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
   var $cm10_i_codigo = 0; 
   var $cm10_i_numpre = 0; 
   var $cm10_d_data_dia = null; 
   var $cm10_d_data_mes = null; 
   var $cm10_d_data_ano = null; 
   var $cm10_d_data = null; 
   var $cm10_i_taxaserv = 0; 
   var $cm10_f_valor = 0; 
   var $cm10_d_privenc_dia = null; 
   var $cm10_d_privenc_mes = null; 
   var $cm10_d_privenc_ano = null; 
   var $cm10_d_privenc = null; 
   var $cm10_t_obs = null; 
   var $cm10_i_usuario = 0; 
   var $cm10_d_dtlanc_dia = null; 
   var $cm10_d_dtlanc_mes = null; 
   var $cm10_d_dtlanc_ano = null; 
   var $cm10_d_dtlanc = null; 
   var $cm10_f_valortaxa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 cm10_i_codigo = int4 = Código 
                 cm10_i_numpre = int4 = Numpre 
                 cm10_d_data = date = Data Sepultamento 
                 cm10_i_taxaserv = int4 = Taxa de Serviço 
                 cm10_f_valor = float8 = Valor Corrigido 
                 cm10_d_privenc = date = Vencimento 
                 cm10_t_obs = text = Observações 
                 cm10_i_usuario = int4 = Usuário 
                 cm10_d_dtlanc = date = Data Lançamento 
                 cm10_f_valortaxa = float8 = Valor Taxa 
                 ";
   //funcao construtor da classe 
   function cl_itenserv() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itenserv"); 
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
       $this->cm10_i_codigo = ($this->cm10_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_i_codigo"]:$this->cm10_i_codigo);
       $this->cm10_i_numpre = ($this->cm10_i_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_i_numpre"]:$this->cm10_i_numpre);
       if($this->cm10_d_data == ""){
         $this->cm10_d_data_dia = ($this->cm10_d_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_d_data_dia"]:$this->cm10_d_data_dia);
         $this->cm10_d_data_mes = ($this->cm10_d_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_d_data_mes"]:$this->cm10_d_data_mes);
         $this->cm10_d_data_ano = ($this->cm10_d_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_d_data_ano"]:$this->cm10_d_data_ano);
         if($this->cm10_d_data_dia != ""){
            $this->cm10_d_data = $this->cm10_d_data_ano."-".$this->cm10_d_data_mes."-".$this->cm10_d_data_dia;
         }
       }
       $this->cm10_i_taxaserv = ($this->cm10_i_taxaserv == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_i_taxaserv"]:$this->cm10_i_taxaserv);
       $this->cm10_f_valor = ($this->cm10_f_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_f_valor"]:$this->cm10_f_valor);
       if($this->cm10_d_privenc == ""){
         $this->cm10_d_privenc_dia = ($this->cm10_d_privenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_d_privenc_dia"]:$this->cm10_d_privenc_dia);
         $this->cm10_d_privenc_mes = ($this->cm10_d_privenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_d_privenc_mes"]:$this->cm10_d_privenc_mes);
         $this->cm10_d_privenc_ano = ($this->cm10_d_privenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_d_privenc_ano"]:$this->cm10_d_privenc_ano);
         if($this->cm10_d_privenc_dia != ""){
            $this->cm10_d_privenc = $this->cm10_d_privenc_ano."-".$this->cm10_d_privenc_mes."-".$this->cm10_d_privenc_dia;
         }
       }
       $this->cm10_t_obs = ($this->cm10_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_t_obs"]:$this->cm10_t_obs);
       $this->cm10_i_usuario = ($this->cm10_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_i_usuario"]:$this->cm10_i_usuario);
       if($this->cm10_d_dtlanc == ""){
         $this->cm10_d_dtlanc_dia = ($this->cm10_d_dtlanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_d_dtlanc_dia"]:$this->cm10_d_dtlanc_dia);
         $this->cm10_d_dtlanc_mes = ($this->cm10_d_dtlanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_d_dtlanc_mes"]:$this->cm10_d_dtlanc_mes);
         $this->cm10_d_dtlanc_ano = ($this->cm10_d_dtlanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_d_dtlanc_ano"]:$this->cm10_d_dtlanc_ano);
         if($this->cm10_d_dtlanc_dia != ""){
            $this->cm10_d_dtlanc = $this->cm10_d_dtlanc_ano."-".$this->cm10_d_dtlanc_mes."-".$this->cm10_d_dtlanc_dia;
         }
       }
       $this->cm10_f_valortaxa = ($this->cm10_f_valortaxa == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_f_valortaxa"]:$this->cm10_f_valortaxa);
     }else{
       $this->cm10_i_codigo = ($this->cm10_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["cm10_i_codigo"]:$this->cm10_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($cm10_i_codigo){ 
      $this->atualizacampos();
     if($this->cm10_i_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "cm10_i_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm10_d_data == null ){ 
       $this->erro_sql = " Campo Data Sepultamento nao Informado.";
       $this->erro_campo = "cm10_d_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm10_i_taxaserv == null ){ 
       $this->erro_sql = " Campo Taxa de Serviço nao Informado.";
       $this->erro_campo = "cm10_i_taxaserv";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm10_f_valor == null ){ 
       $this->erro_sql = " Campo Valor Corrigido nao Informado.";
       $this->erro_campo = "cm10_f_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm10_d_privenc == null ){ 
       $this->erro_sql = " Campo Vencimento nao Informado.";
       $this->erro_campo = "cm10_d_privenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm10_i_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "cm10_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm10_d_dtlanc == null ){ 
       $this->erro_sql = " Campo Data Lançamento nao Informado.";
       $this->erro_campo = "cm10_d_dtlanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cm10_f_valortaxa == null ){ 
       $this->erro_sql = " Campo Valor Taxa nao Informado.";
       $this->erro_campo = "cm10_f_valortaxa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cm10_i_codigo == "" || $cm10_i_codigo == null ){
       $result = db_query("select nextval('itenserv_cm10_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: itenserv_cm10_i_codigo_seq do campo: cm10_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->cm10_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from itenserv_cm10_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $cm10_i_codigo)){
         $this->erro_sql = " Campo cm10_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cm10_i_codigo = $cm10_i_codigo; 
       }
     }
     if(($this->cm10_i_codigo == null) || ($this->cm10_i_codigo == "") ){ 
       $this->erro_sql = " Campo cm10_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itenserv(
                                       cm10_i_codigo 
                                      ,cm10_i_numpre 
                                      ,cm10_d_data 
                                      ,cm10_i_taxaserv 
                                      ,cm10_f_valor 
                                      ,cm10_d_privenc 
                                      ,cm10_t_obs 
                                      ,cm10_i_usuario 
                                      ,cm10_d_dtlanc 
                                      ,cm10_f_valortaxa 
                       )
                values (
                                $this->cm10_i_codigo 
                               ,$this->cm10_i_numpre 
                               ,".($this->cm10_d_data == "null" || $this->cm10_d_data == ""?"null":"'".$this->cm10_d_data."'")." 
                               ,$this->cm10_i_taxaserv 
                               ,$this->cm10_f_valor 
                               ,".($this->cm10_d_privenc == "null" || $this->cm10_d_privenc == ""?"null":"'".$this->cm10_d_privenc."'")." 
                               ,'$this->cm10_t_obs' 
                               ,$this->cm10_i_usuario 
                               ,".($this->cm10_d_dtlanc == "null" || $this->cm10_d_dtlanc == ""?"null":"'".$this->cm10_d_dtlanc."'")." 
                               ,$this->cm10_f_valortaxa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Itens de Serviço ($this->cm10_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Itens de Serviço já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Itens de Serviço ($this->cm10_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm10_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->cm10_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10319,'$this->cm10_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1788,10319,'','".AddSlashes(pg_result($resaco,0,'cm10_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1788,10320,'','".AddSlashes(pg_result($resaco,0,'cm10_i_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1788,10321,'','".AddSlashes(pg_result($resaco,0,'cm10_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1788,10322,'','".AddSlashes(pg_result($resaco,0,'cm10_i_taxaserv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1788,10323,'','".AddSlashes(pg_result($resaco,0,'cm10_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1788,10324,'','".AddSlashes(pg_result($resaco,0,'cm10_d_privenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1788,10328,'','".AddSlashes(pg_result($resaco,0,'cm10_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1788,10329,'','".AddSlashes(pg_result($resaco,0,'cm10_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1788,15588,'','".AddSlashes(pg_result($resaco,0,'cm10_d_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1788,15589,'','".AddSlashes(pg_result($resaco,0,'cm10_f_valortaxa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($cm10_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update itenserv set ";
     $virgula = "";
     if(trim($this->cm10_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm10_i_codigo"])){ 
       $sql  .= $virgula." cm10_i_codigo = $this->cm10_i_codigo ";
       $virgula = ",";
       if(trim($this->cm10_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "cm10_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm10_i_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm10_i_numpre"])){ 
       $sql  .= $virgula." cm10_i_numpre = $this->cm10_i_numpre ";
       $virgula = ",";
       if(trim($this->cm10_i_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "cm10_i_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm10_d_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm10_d_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm10_d_data_dia"] !="") ){ 
       $sql  .= $virgula." cm10_d_data = '$this->cm10_d_data' ";
       $virgula = ",";
       if(trim($this->cm10_d_data) == null ){ 
         $this->erro_sql = " Campo Data Sepultamento nao Informado.";
         $this->erro_campo = "cm10_d_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm10_d_data_dia"])){ 
         $sql  .= $virgula." cm10_d_data = null ";
         $virgula = ",";
         if(trim($this->cm10_d_data) == null ){ 
           $this->erro_sql = " Campo Data Sepultamento nao Informado.";
           $this->erro_campo = "cm10_d_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm10_i_taxaserv)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm10_i_taxaserv"])){ 
       $sql  .= $virgula." cm10_i_taxaserv = $this->cm10_i_taxaserv ";
       $virgula = ",";
       if(trim($this->cm10_i_taxaserv) == null ){ 
         $this->erro_sql = " Campo Taxa de Serviço nao Informado.";
         $this->erro_campo = "cm10_i_taxaserv";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm10_f_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm10_f_valor"])){ 
       $sql  .= $virgula." cm10_f_valor = $this->cm10_f_valor ";
       $virgula = ",";
       if(trim($this->cm10_f_valor) == null ){ 
         $this->erro_sql = " Campo Valor Corrigido nao Informado.";
         $this->erro_campo = "cm10_f_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm10_d_privenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm10_d_privenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm10_d_privenc_dia"] !="") ){ 
       $sql  .= $virgula." cm10_d_privenc = '$this->cm10_d_privenc' ";
       $virgula = ",";
       if(trim($this->cm10_d_privenc) == null ){ 
         $this->erro_sql = " Campo Vencimento nao Informado.";
         $this->erro_campo = "cm10_d_privenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm10_d_privenc_dia"])){ 
         $sql  .= $virgula." cm10_d_privenc = null ";
         $virgula = ",";
         if(trim($this->cm10_d_privenc) == null ){ 
           $this->erro_sql = " Campo Vencimento nao Informado.";
           $this->erro_campo = "cm10_d_privenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm10_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm10_t_obs"])){ 
       $sql  .= $virgula." cm10_t_obs = '$this->cm10_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->cm10_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm10_i_usuario"])){ 
       $sql  .= $virgula." cm10_i_usuario = $this->cm10_i_usuario ";
       $virgula = ",";
       if(trim($this->cm10_i_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "cm10_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cm10_d_dtlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm10_d_dtlanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["cm10_d_dtlanc_dia"] !="") ){ 
       $sql  .= $virgula." cm10_d_dtlanc = '$this->cm10_d_dtlanc' ";
       $virgula = ",";
       if(trim($this->cm10_d_dtlanc) == null ){ 
         $this->erro_sql = " Campo Data Lançamento nao Informado.";
         $this->erro_campo = "cm10_d_dtlanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["cm10_d_dtlanc_dia"])){ 
         $sql  .= $virgula." cm10_d_dtlanc = null ";
         $virgula = ",";
         if(trim($this->cm10_d_dtlanc) == null ){ 
           $this->erro_sql = " Campo Data Lançamento nao Informado.";
           $this->erro_campo = "cm10_d_dtlanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->cm10_f_valortaxa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cm10_f_valortaxa"])){ 
       $sql  .= $virgula." cm10_f_valortaxa = $this->cm10_f_valortaxa ";
       $virgula = ",";
       if(trim($this->cm10_f_valortaxa) == null ){ 
         $this->erro_sql = " Campo Valor Taxa nao Informado.";
         $this->erro_campo = "cm10_f_valortaxa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($cm10_i_codigo!=null){
       $sql .= " cm10_i_codigo = $this->cm10_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->cm10_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10319,'$this->cm10_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm10_i_codigo"]) || $this->cm10_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,1788,10319,'".AddSlashes(pg_result($resaco,$conresaco,'cm10_i_codigo'))."','$this->cm10_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm10_i_numpre"]) || $this->cm10_i_numpre != "")
           $resac = db_query("insert into db_acount values($acount,1788,10320,'".AddSlashes(pg_result($resaco,$conresaco,'cm10_i_numpre'))."','$this->cm10_i_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm10_d_data"]) || $this->cm10_d_data != "")
           $resac = db_query("insert into db_acount values($acount,1788,10321,'".AddSlashes(pg_result($resaco,$conresaco,'cm10_d_data'))."','$this->cm10_d_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm10_i_taxaserv"]) || $this->cm10_i_taxaserv != "")
           $resac = db_query("insert into db_acount values($acount,1788,10322,'".AddSlashes(pg_result($resaco,$conresaco,'cm10_i_taxaserv'))."','$this->cm10_i_taxaserv',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm10_f_valor"]) || $this->cm10_f_valor != "")
           $resac = db_query("insert into db_acount values($acount,1788,10323,'".AddSlashes(pg_result($resaco,$conresaco,'cm10_f_valor'))."','$this->cm10_f_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm10_d_privenc"]) || $this->cm10_d_privenc != "")
           $resac = db_query("insert into db_acount values($acount,1788,10324,'".AddSlashes(pg_result($resaco,$conresaco,'cm10_d_privenc'))."','$this->cm10_d_privenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm10_t_obs"]) || $this->cm10_t_obs != "")
           $resac = db_query("insert into db_acount values($acount,1788,10328,'".AddSlashes(pg_result($resaco,$conresaco,'cm10_t_obs'))."','$this->cm10_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm10_i_usuario"]) || $this->cm10_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,1788,10329,'".AddSlashes(pg_result($resaco,$conresaco,'cm10_i_usuario'))."','$this->cm10_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm10_d_dtlanc"]) || $this->cm10_d_dtlanc != "")
           $resac = db_query("insert into db_acount values($acount,1788,15588,'".AddSlashes(pg_result($resaco,$conresaco,'cm10_d_dtlanc'))."','$this->cm10_d_dtlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["cm10_f_valortaxa"]) || $this->cm10_f_valortaxa != "")
           $resac = db_query("insert into db_acount values($acount,1788,15589,'".AddSlashes(pg_result($resaco,$conresaco,'cm10_f_valortaxa'))."','$this->cm10_f_valortaxa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens de Serviço nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm10_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens de Serviço nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cm10_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cm10_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($cm10_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($cm10_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10319,'$cm10_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1788,10319,'','".AddSlashes(pg_result($resaco,$iresaco,'cm10_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1788,10320,'','".AddSlashes(pg_result($resaco,$iresaco,'cm10_i_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1788,10321,'','".AddSlashes(pg_result($resaco,$iresaco,'cm10_d_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1788,10322,'','".AddSlashes(pg_result($resaco,$iresaco,'cm10_i_taxaserv'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1788,10323,'','".AddSlashes(pg_result($resaco,$iresaco,'cm10_f_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1788,10324,'','".AddSlashes(pg_result($resaco,$iresaco,'cm10_d_privenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1788,10328,'','".AddSlashes(pg_result($resaco,$iresaco,'cm10_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1788,10329,'','".AddSlashes(pg_result($resaco,$iresaco,'cm10_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1788,15588,'','".AddSlashes(pg_result($resaco,$iresaco,'cm10_d_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1788,15589,'','".AddSlashes(pg_result($resaco,$iresaco,'cm10_f_valortaxa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from itenserv
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($cm10_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " cm10_i_codigo = $cm10_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Itens de Serviço nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cm10_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Itens de Serviço nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cm10_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cm10_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:itenserv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $cm10_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itenserv ";
     $sql .= " inner join db_usuarios  on  db_usuarios.id_usuario = itenserv.cm10_i_usuario";
     $sql .= " left join txsepultamentos on txsepultamentos.cm31_i_itenserv = itenserv.cm10_i_codigo ";
     $sql .= " left join sepultamentos  on  sepultamentos.cm01_i_codigo = txsepultamentos.cm31_i_sepultamento";
     $sql .= " left join cgm cgmsepultamento on  cgmsepultamento.z01_numcgm = sepultamentos.cm01_i_codigo";
     $sql .= " left join txossoariojazigo on txossoariojazigo.cm30_i_itenserv = itenserv.cm10_i_codigo ";
     $sql .= " left join propricemit on propricemit.cm28_i_ossoariojazigo = txossoariojazigo.cm30_i_ossoariojazigo ";
     $sql .= " left join cgm cgmossoariojazigo on  cgmossoariojazigo.z01_numcgm = propricemit.cm28_i_proprietario";
     $sql .= " inner join taxaserv  on  taxaserv.cm11_i_codigo   = itenserv.cm10_i_taxaserv";
     $sql .= " inner join tabrec    on  tabrec.k02_codigo        = taxaserv.cm11_i_receita";
     $sql .= " inner join arretipo  on  arretipo.k00_tipo        = taxaserv.cm11_i_tipo";
     $sql .= " inner join procdiver on  procdiver.dv09_procdiver = taxaserv.cm11_i_proced";
     $sql2 = "";
     if($dbwhere==""){
       if($cm10_i_codigo!=null ){
         $sql2 .= " where itenserv.cm10_i_codigo = $cm10_i_codigo "; 
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
   function sql_query_file ( $cm10_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itenserv ";
     $sql2 = "";
     if($dbwhere==""){
       if($cm10_i_codigo!=null ){
         $sql2 .= " where itenserv.cm10_i_codigo = $cm10_i_codigo "; 
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