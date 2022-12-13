<?php
/**
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE assenta
class cl_assenta { 
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
   var $h16_codigo = 0; 
   var $h16_regist = 0; 
   var $h16_assent = 0; 
   var $h16_dtconc_dia = null; 
   var $h16_dtconc_mes = null; 
   var $h16_dtconc_ano = null; 
   var $h16_dtconc = null; 
   var $h16_histor = null; 
   var $h16_nrport = null; 
   var $h16_atofic = null; 
   var $h16_quant = 0; 
   var $h16_perc = 0; 
   var $h16_dtterm_dia = null; 
   var $h16_dtterm_mes = null; 
   var $h16_dtterm_ano = null; 
   var $h16_dtterm = null; 
   var $h16_hist2 = null; 
   var $h16_login = 0; 
   var $h16_dtlanc_dia = null; 
   var $h16_dtlanc_mes = null; 
   var $h16_dtlanc_ano = null; 
   var $h16_dtlanc = null; 
   var $h16_conver = 'f'; 
   var $h16_anoato = 0; 
   var $h16_hora = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h16_codigo = int4 = Código do Assentamento 
                 h16_regist = int4 = Servidor 
                 h16_assent = int4 = Afast / Assent 
                 h16_dtconc = date = Data inicial 
                 h16_histor = text = Histórico 
                 h16_nrport = varchar(10) = Número ato 
                 h16_atofic = varchar(15) = Tipo ato 
                 h16_quant = int4 = Quant. dias 
                 h16_perc = float8 = Percentual concedido 
                 h16_dtterm = date = Data final 
                 h16_hist2 = varchar(240) = Histórico 2 
                 h16_login = int4 = Login 
                 h16_dtlanc = date = Data do Lancamento 
                 h16_conver = boolean = MARCAR SE REGISTRO FOI CONVERT 
                 h16_anoato = int4 = Ano ato 
                 h16_hora = varchar(5) = Horas 
                 ";
   //funcao construtor da classe 
   function cl_assenta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("assenta"); 
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
       $this->h16_codigo = ($this->h16_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_codigo"]:$this->h16_codigo);
       $this->h16_regist = ($this->h16_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_regist"]:$this->h16_regist);
       $this->h16_assent = ($this->h16_assent == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_assent"]:$this->h16_assent);
       if($this->h16_dtconc == ""){
         $this->h16_dtconc_dia = ($this->h16_dtconc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_dtconc_dia"]:$this->h16_dtconc_dia);
         $this->h16_dtconc_mes = ($this->h16_dtconc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_dtconc_mes"]:$this->h16_dtconc_mes);
         $this->h16_dtconc_ano = ($this->h16_dtconc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_dtconc_ano"]:$this->h16_dtconc_ano);
         if($this->h16_dtconc_dia != ""){
            $this->h16_dtconc = $this->h16_dtconc_ano."-".$this->h16_dtconc_mes."-".$this->h16_dtconc_dia;
         }
       }
       $this->h16_histor = ($this->h16_histor == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_histor"]:$this->h16_histor);
       $this->h16_nrport = ($this->h16_nrport == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_nrport"]:$this->h16_nrport);
       $this->h16_atofic = ($this->h16_atofic == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_atofic"]:$this->h16_atofic);
       $this->h16_quant = ($this->h16_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_quant"]:$this->h16_quant);
       $this->h16_perc = ($this->h16_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_perc"]:$this->h16_perc);
       if($this->h16_dtterm == ""){
         $this->h16_dtterm_dia = ($this->h16_dtterm_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_dtterm_dia"]:$this->h16_dtterm_dia);
         $this->h16_dtterm_mes = ($this->h16_dtterm_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_dtterm_mes"]:$this->h16_dtterm_mes);
         $this->h16_dtterm_ano = ($this->h16_dtterm_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_dtterm_ano"]:$this->h16_dtterm_ano);
         if($this->h16_dtterm_dia != ""){
            $this->h16_dtterm = $this->h16_dtterm_ano."-".$this->h16_dtterm_mes."-".$this->h16_dtterm_dia;
         }
       }
       $this->h16_hist2 = ($this->h16_hist2 == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_hist2"]:$this->h16_hist2);
       $this->h16_login = ($this->h16_login == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_login"]:$this->h16_login);
       if($this->h16_dtlanc == ""){
         $this->h16_dtlanc_dia = ($this->h16_dtlanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_dtlanc_dia"]:$this->h16_dtlanc_dia);
         $this->h16_dtlanc_mes = ($this->h16_dtlanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_dtlanc_mes"]:$this->h16_dtlanc_mes);
         $this->h16_dtlanc_ano = ($this->h16_dtlanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_dtlanc_ano"]:$this->h16_dtlanc_ano);
         if($this->h16_dtlanc_dia != ""){
            $this->h16_dtlanc = $this->h16_dtlanc_ano."-".$this->h16_dtlanc_mes."-".$this->h16_dtlanc_dia;
         }
       }
       $this->h16_conver = ($this->h16_conver == "f"?@$GLOBALS["HTTP_POST_VARS"]["h16_conver"]:$this->h16_conver);
       $this->h16_anoato = ($this->h16_anoato == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_anoato"]:$this->h16_anoato);
       $this->h16_hora = ($this->h16_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_hora"]:$this->h16_hora);
     }else{
       $this->h16_codigo = ($this->h16_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h16_codigo"]:$this->h16_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($h16_codigo){ 
      $this->atualizacampos();
     if($this->h16_regist == null ){ 
       $this->erro_sql = " Campo Servidor não informado.";
       $this->erro_campo = "h16_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h16_assent == null ){ 
       $this->erro_sql = " Campo Afast / Assent não informado.";
       $this->erro_campo = "h16_assent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h16_dtconc == null ){ 
       $this->erro_sql = " Campo Data inicial não informado.";
       $this->erro_campo = "h16_dtconc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h16_quant == null ){ 
       $this->h16_quant = "0";
     }
     if($this->h16_perc == null ){ 
       $this->erro_sql = " Campo Qtde de Horas não informado.";
       $this->erro_campo = "h16_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h16_dtterm == null ){ 
       $this->h16_dtterm = "null";
     }
     if($this->h16_login == null ){ 
       $this->erro_sql = " Campo Login não informado.";
       $this->erro_campo = "h16_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h16_dtlanc == null ){ 
       $this->erro_sql = " Campo Data do Lancamento não informado.";
       $this->erro_campo = "h16_dtlanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h16_conver == null ){ 
       $this->erro_sql = " Campo MARCAR SE REGISTRO FOI CONVERT não informado.";
       $this->erro_campo = "h16_conver";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h16_anoato == null ){ 
       $this->h16_anoato = "0";
     }
     if($h16_codigo == "" || $h16_codigo == null ){
       $result = db_query("select nextval('assenta_h16_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: assenta_h16_codigo_seq do campo: h16_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h16_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from assenta_h16_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $h16_codigo)){
         $this->erro_sql = " Campo h16_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h16_codigo = $h16_codigo; 
       }
     }
     if(($this->h16_codigo == null) || ($this->h16_codigo == "") ){ 
       $this->erro_sql = " Campo h16_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into assenta(
                                       h16_codigo 
                                      ,h16_regist 
                                      ,h16_assent 
                                      ,h16_dtconc 
                                      ,h16_histor 
                                      ,h16_nrport 
                                      ,h16_atofic 
                                      ,h16_quant 
                                      ,h16_perc 
                                      ,h16_dtterm 
                                      ,h16_hist2 
                                      ,h16_login 
                                      ,h16_dtlanc 
                                      ,h16_conver 
                                      ,h16_anoato 
                                      ,h16_hora 
                       )
                values (
                                $this->h16_codigo 
                               ,$this->h16_regist 
                               ,$this->h16_assent 
                               ,".($this->h16_dtconc == "null" || $this->h16_dtconc == ""?"null":"'".$this->h16_dtconc."'")." 
                               ,'$this->h16_histor' 
                               ,'$this->h16_nrport' 
                               ,'$this->h16_atofic' 
                               ,$this->h16_quant 
                               ,$this->h16_perc 
                               ,".($this->h16_dtterm == "null" || $this->h16_dtterm == ""?"null":"'".$this->h16_dtterm."'")." 
                               ,'$this->h16_hist2' 
                               ,$this->h16_login 
                               ,".($this->h16_dtlanc == "null" || $this->h16_dtlanc == ""?"null":"'".$this->h16_dtlanc."'")." 
                               ,'$this->h16_conver' 
                               ,$this->h16_anoato 
                               ,'$this->h16_hora' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Assentamentos por funcionario                      ($this->h16_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Assentamentos por funcionario                      já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Assentamentos por funcionario                      ($this->h16_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->h16_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h16_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9551,'$this->h16_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,528,9551,'','".AddSlashes(pg_result($resaco,0,'h16_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,3660,'','".AddSlashes(pg_result($resaco,0,'h16_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,3661,'','".AddSlashes(pg_result($resaco,0,'h16_assent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,3662,'','".AddSlashes(pg_result($resaco,0,'h16_dtconc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,3663,'','".AddSlashes(pg_result($resaco,0,'h16_histor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,3664,'','".AddSlashes(pg_result($resaco,0,'h16_nrport'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,3665,'','".AddSlashes(pg_result($resaco,0,'h16_atofic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,3666,'','".AddSlashes(pg_result($resaco,0,'h16_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,3667,'','".AddSlashes(pg_result($resaco,0,'h16_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,3668,'','".AddSlashes(pg_result($resaco,0,'h16_dtterm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,3669,'','".AddSlashes(pg_result($resaco,0,'h16_hist2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,3670,'','".AddSlashes(pg_result($resaco,0,'h16_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,3671,'','".AddSlashes(pg_result($resaco,0,'h16_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,3672,'','".AddSlashes(pg_result($resaco,0,'h16_conver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,14198,'','".AddSlashes(pg_result($resaco,0,'h16_anoato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,528,1009327,'','".AddSlashes(pg_result($resaco,0,'h16_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($h16_codigo=null) { 
      $this->atualizacampos();
     $sql = " update assenta set ";
     $virgula = "";
     if(trim($this->h16_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_codigo"])){ 
       $sql  .= $virgula." h16_codigo = $this->h16_codigo ";
       $virgula = ",";
       if(trim($this->h16_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Assentamento não informado.";
         $this->erro_campo = "h16_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h16_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_regist"])){ 
       $sql  .= $virgula." h16_regist = $this->h16_regist ";
       $virgula = ",";
       if(trim($this->h16_regist) == null ){ 
         $this->erro_sql = " Campo Servidor não informado.";
         $this->erro_campo = "h16_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h16_assent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_assent"])){ 
       $sql  .= $virgula." h16_assent = $this->h16_assent ";
       $virgula = ",";
       if(trim($this->h16_assent) == null ){ 
         $this->erro_sql = " Campo Afast / Assent não informado.";
         $this->erro_campo = "h16_assent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h16_dtconc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_dtconc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h16_dtconc_dia"] !="") ){ 
       $sql  .= $virgula." h16_dtconc = '$this->h16_dtconc' ";
       $virgula = ",";
       if(trim($this->h16_dtconc) == null ){ 
         $this->erro_sql = " Campo Data inicial não informado.";
         $this->erro_campo = "h16_dtconc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h16_dtconc_dia"])){ 
         $sql  .= $virgula." h16_dtconc = null ";
         $virgula = ",";
         if(trim($this->h16_dtconc) == null ){ 
           $this->erro_sql = " Campo Data inicial não informado.";
           $this->erro_campo = "h16_dtconc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h16_histor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_histor"])){ 
       $sql  .= $virgula." h16_histor = '$this->h16_histor' ";
       $virgula = ",";
     }
     if(trim($this->h16_nrport)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_nrport"])){ 
       $sql  .= $virgula." h16_nrport = '$this->h16_nrport' ";
       $virgula = ",";
     }
     if(trim($this->h16_atofic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_atofic"])){ 
       $sql  .= $virgula." h16_atofic = '$this->h16_atofic' ";
       $virgula = ",";
     }
     if(trim($this->h16_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_quant"])){ 
        if(trim($this->h16_quant)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h16_quant"])){ 
           $this->h16_quant = "0" ; 
        } 
       $sql  .= $virgula." h16_quant = $this->h16_quant ";
       $virgula = ",";
     }
     if(trim($this->h16_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_perc"])){ 
       $sql  .= $virgula." h16_perc = $this->h16_perc ";
       $virgula = ",";
       if(trim($this->h16_perc) == null ){ 
         $this->erro_sql = " Campo Qtde de Horas não informado.";
         $this->erro_campo = "h16_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h16_dtterm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_dtterm_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h16_dtterm_dia"] !="") ){ 
       $sql  .= $virgula." h16_dtterm = '$this->h16_dtterm' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h16_dtterm_dia"])){ 
         $sql  .= $virgula." h16_dtterm = null ";
         $virgula = ",";
       }
     }
     if(trim($this->h16_hist2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_hist2"])){ 
       $sql  .= $virgula." h16_hist2 = '$this->h16_hist2' ";
       $virgula = ",";
     }
     if(trim($this->h16_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_login"])){ 
       $sql  .= $virgula." h16_login = $this->h16_login ";
       $virgula = ",";
       if(trim($this->h16_login) == null ){ 
         $this->erro_sql = " Campo Login não informado.";
         $this->erro_campo = "h16_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h16_dtlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_dtlanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h16_dtlanc_dia"] !="") ){ 
       $sql  .= $virgula." h16_dtlanc = '$this->h16_dtlanc' ";
       $virgula = ",";
       if(trim($this->h16_dtlanc) == null ){ 
         $this->erro_sql = " Campo Data do Lancamento não informado.";
         $this->erro_campo = "h16_dtlanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h16_dtlanc_dia"])){ 
         $sql  .= $virgula." h16_dtlanc = null ";
         $virgula = ",";
         if(trim($this->h16_dtlanc) == null ){ 
           $this->erro_sql = " Campo Data do Lancamento não informado.";
           $this->erro_campo = "h16_dtlanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h16_conver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_conver"])){ 
       $sql  .= $virgula." h16_conver = '$this->h16_conver' ";
       $virgula = ",";
       if(trim($this->h16_conver) == null ){ 
         $this->erro_sql = " Campo MARCAR SE REGISTRO FOI CONVERT não informado.";
         $this->erro_campo = "h16_conver";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h16_anoato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_anoato"])){ 
        if(trim($this->h16_anoato)=="" && isset($GLOBALS["HTTP_POST_VARS"]["h16_anoato"])){ 
           $this->h16_anoato = "0" ; 
        } 
       $sql  .= $virgula." h16_anoato = $this->h16_anoato ";
       $virgula = ",";
     }
     if(trim($this->h16_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h16_hora"])){ 
       $sql  .= $virgula." h16_hora = '$this->h16_hora' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($h16_codigo!=null){
       $sql .= " h16_codigo = $this->h16_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->h16_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,9551,'$this->h16_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_codigo"]) || $this->h16_codigo != "")
             $resac = db_query("insert into db_acount values($acount,528,9551,'".AddSlashes(pg_result($resaco,$conresaco,'h16_codigo'))."','$this->h16_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_regist"]) || $this->h16_regist != "")
             $resac = db_query("insert into db_acount values($acount,528,3660,'".AddSlashes(pg_result($resaco,$conresaco,'h16_regist'))."','$this->h16_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_assent"]) || $this->h16_assent != "")
             $resac = db_query("insert into db_acount values($acount,528,3661,'".AddSlashes(pg_result($resaco,$conresaco,'h16_assent'))."','$this->h16_assent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_dtconc"]) || $this->h16_dtconc != "")
             $resac = db_query("insert into db_acount values($acount,528,3662,'".AddSlashes(pg_result($resaco,$conresaco,'h16_dtconc'))."','$this->h16_dtconc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_histor"]) || $this->h16_histor != "")
             $resac = db_query("insert into db_acount values($acount,528,3663,'".AddSlashes(pg_result($resaco,$conresaco,'h16_histor'))."','$this->h16_histor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_nrport"]) || $this->h16_nrport != "")
             $resac = db_query("insert into db_acount values($acount,528,3664,'".AddSlashes(pg_result($resaco,$conresaco,'h16_nrport'))."','$this->h16_nrport',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_atofic"]) || $this->h16_atofic != "")
             $resac = db_query("insert into db_acount values($acount,528,3665,'".AddSlashes(pg_result($resaco,$conresaco,'h16_atofic'))."','$this->h16_atofic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_quant"]) || $this->h16_quant != "")
             $resac = db_query("insert into db_acount values($acount,528,3666,'".AddSlashes(pg_result($resaco,$conresaco,'h16_quant'))."','$this->h16_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_perc"]) || $this->h16_perc != "")
             $resac = db_query("insert into db_acount values($acount,528,3667,'".AddSlashes(pg_result($resaco,$conresaco,'h16_perc'))."','$this->h16_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_dtterm"]) || $this->h16_dtterm != "")
             $resac = db_query("insert into db_acount values($acount,528,3668,'".AddSlashes(pg_result($resaco,$conresaco,'h16_dtterm'))."','$this->h16_dtterm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_hist2"]) || $this->h16_hist2 != "")
             $resac = db_query("insert into db_acount values($acount,528,3669,'".AddSlashes(pg_result($resaco,$conresaco,'h16_hist2'))."','$this->h16_hist2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_login"]) || $this->h16_login != "")
             $resac = db_query("insert into db_acount values($acount,528,3670,'".AddSlashes(pg_result($resaco,$conresaco,'h16_login'))."','$this->h16_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_dtlanc"]) || $this->h16_dtlanc != "")
             $resac = db_query("insert into db_acount values($acount,528,3671,'".AddSlashes(pg_result($resaco,$conresaco,'h16_dtlanc'))."','$this->h16_dtlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_conver"]) || $this->h16_conver != "")
             $resac = db_query("insert into db_acount values($acount,528,3672,'".AddSlashes(pg_result($resaco,$conresaco,'h16_conver'))."','$this->h16_conver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_anoato"]) || $this->h16_anoato != "")
             $resac = db_query("insert into db_acount values($acount,528,14198,'".AddSlashes(pg_result($resaco,$conresaco,'h16_anoato'))."','$this->h16_anoato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["h16_hora"]) || $this->h16_hora != "")
             $resac = db_query("insert into db_acount values($acount,528,1009327,'".AddSlashes(pg_result($resaco,$conresaco,'h16_hora'))."','$this->h16_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Assentamentos por funcionario                      não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h16_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Assentamentos por funcionario                      não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h16_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->h16_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($h16_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($h16_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,9551,'$h16_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,528,9551,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,3660,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,3661,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_assent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,3662,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_dtconc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,3663,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_histor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,3664,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_nrport'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,3665,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_atofic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,3666,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,3667,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,3668,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_dtterm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,3669,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_hist2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,3670,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,3671,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,3672,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_conver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,14198,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_anoato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,528,1009327,'','".AddSlashes(pg_result($resaco,$iresaco,'h16_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from assenta
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($h16_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " h16_codigo = $h16_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Assentamentos por funcionario                      não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h16_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Assentamentos por funcionario                      não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h16_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$h16_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:assenta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
  function sql_query ( $h16_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from assenta ";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = assenta.h16_login";
    $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = assenta.h16_assent";
    $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = assenta.h16_regist";
    $sql .= "      inner join rhpessoalmov on rh02_anousu = ".db_anofolha()." 
                                           and rh02_mesusu = ".db_mesfolha()."
                                           and rh02_regist = rh01_regist
                                           and rh02_instit = ".db_getsession('DB_instit');
    $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
    $sql .= "      inner join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
    $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
    $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
    $sql .= "      inner join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao
                                         and rh37_instit = ".db_getsession('DB_instit');
    $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
    $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
    $sql .= "       left join portariatipo     on  portariatipo.h30_tipoasse = tipoasse.h12_codigo";
    $sql2 = "";
    if($dbwhere==""){
      if($h16_codigo!=null ){
        $sql2 .= " where assenta.h16_codigo = $h16_codigo ";
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
   public function sql_query_file ($h16_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from assenta ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($h16_codigo)){
         $sql2 .= " where assenta.h16_codigo = $h16_codigo "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

   function sql_query_tipo ( $h16_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from assenta ";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = assenta.h16_assent";
     $sql2 = "";
     if($dbwhere==""){
       if($h16_codigo!=null ){
         $sql2 .= " where assenta.h16_codigo = $h16_codigo ";
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
   
   /**
 * Lista as perdas da matricula
 */   
function sql_query_perdasMatricula($iMatricula, $dtInicio, $dtFim = null, $sCampos = "*", $sGroupBy) {

  if ( empty($dtFim) ) {        
    $dtFim = date('Y-m-d', db_getsession("DB_datausu"));
  }
          
  $sSql  = "select {$sCampos}                                                                          ";
  $sSql .= "  from assenta                                                                             ";
  $sSql .= "       inner join tipoasse                    on h16_assent           = h12_codigo         ";
  $sSql .= "       inner join rhtipoperdatipoassentamento on h71_tipoassentamento = h12_codigo         ";
  $sSql .= "       inner join rhtipoperda                 on h70_sequencial       = h71_rhtipoperda    "; 
  $sSql .= " where h16_regist = {$iMatricula}                                                          ";
  $sSql .="    and case when h16_dtterm is null                                                        ";
    $sSql .=" 	           then true                                                                     ";
  $sSql .="             else (h16_dtconc, h16_dtterm) overlaps ('{$dtInicio}'::date, '{$dtFim}'::date) ";
  $sSql .="        end                                                                                 ";
  $sSql .="{$sGroupBy}                                                                                 ";
    
  return $sSql;
}

  
  function sql_query_perdasTipoPerda($iMatricula, $aTiposPerda, $dtInicio, $dtFim = null) {
    
    if ( empty($dtFim) ) {
      $dtFim = date('Y-m-d', db_getsession("DB_datausu"));
    }
    
    $sTipoPerda = implode(', ', $aTiposPerda);
    
    $sSql  = "select h16_codigo, h70_descricao, h16_quant , h70_dias                                     ";
    $sSql .= "  from assenta                                                                             ";
    $sSql .= "       inner join tipoasse                    on h16_assent           = h12_codigo         ";
    $sSql .= "       inner join rhtipoperdatipoassentamento on h71_tipoassentamento = h12_codigo         ";
    $sSql .= "       inner join rhtipoperda                 on h70_sequencial       = h71_rhtipoperda    ";
    $sSql .= " where h16_regist     = {$iMatricula}                                                      ";
    $sSql .= "   and h70_sequencial in ({$sTipoPerda})                                                   ";
    $sSql .= "   and case when h16_dtterm is null                                                        ";
    $sSql .= " 	          then true                                                                      ";
    $sSql .= "            else (h16_dtconc, h16_dtterm) overlaps ('{$dtInicio}'::date, '{$dtFim}'::date) ";
    $sSql .= "       end                                                                                 ";

    return $sSql;
  }

  /**
   * Busca informacoes dos assentamentos do servidor
   * @param string $sCampos
   * @param string $sOrderBy
   * @param string $sWhere
   */
   function sql_query_assentamentos($sCampos, $sOrderBy, $sWhere) {

    $sSql  = "select {$sCampos}                                                                ";
    $sSql .= "  from assenta                                                                   ";
    $sSql .= "       inner join tipoasse     on h16_assent             = h12_codigo            ";
    $sSql .= "       inner join rhpessoal    on rhpessoal.rh01_regist  = assenta.h16_regist    ";
    $sSql .= "       inner join cgm          on cgm.z01_numcgm         = rhpessoal.rh01_numcgm ";
    $sSql .= "       inner join rhfuncao     on rhfuncao.rh37_funcao   = rhpessoal.rh01_funcao ";
    $sSql .= " where {$sWhere}                                                                 ";

    if ( !empty($sOrderBy) ) {
      $sSql .= " order by ". $sOrderBy;
    }

    return $sSql;
  }

  function sql_saldoDiasDireito($iPeriodo, $iCodigoAssentamento = '', $iSequencialAssentamento = '') {

    $sSql  = "select rh109_diasdireito,                                                                                       "; 
    $sSql .= "       rh109_diasdireito + coalesce(( select sum( coalesce(case                                                 "; 
    $sSql .= "                                                             when h40_lancahaver = 1 then h16_quant             "; 
    $sSql .= "                                                             when h40_lancahaver = 2 then (h16_quant*-1)        "; 
    $sSql .= "                                                           end , 0) )                                           "; 
    $sSql .= "                                        from assenta                                                            "; 
    $sSql .= "                                             inner join tipoasse        on h12_codigo      = h16_assent         "; 
    $sSql .= "                                             inner join portariatipo    on h30_tipoasse    = h12_codigo         "; 
    $sSql .= "                                             inner join rhferiasassenta on rh131_assenta   = h16_codigo         "; 
    $sSql .= "                                             inner join portariaproced  on h40_sequencial  = h30_portariaproced "; 
    $sSql .= "                                       where rh131_rhferias = rh109_sequencial                                  "; 

    if( !empty($iCodigoAssentamento) ){
       $sSql .= " and rh131_assenta <> {$iCodigoAssentamento}  ";
}

    if( !empty($iSequencialAssentamento) ){
      $sSql .= " and h16_codigo <> {$iSequencialAssentamento}                                  ";      
    }

    $sSql .= "                                         and h12_vinculaperiodoaquisitivo is true ),0) as saldodiasdireito      "; 
    $sSql .= "  from rhferias                                                                                                 "; 
    $sSql .= " where rh109_sequencial = {$iPeriodo}                                                                           "; 

    

    return $sSql;
  }

  function sql_validaPeriodoGozoFerias($iCodigoServidor, $iTipoAssentamento, $sDataInicial, $sDataFinal, $iSequencialAssentamento = '') {


    $sSql  = "select *                                                                         ";
    $sSql .= "  from  assenta                                                                  ";
    $sSql .= "        inner join tipoasse on h12_codigo           = h16_assent                 ";
    $sSql .= "        inner join portariatipo on h30_tipoasse     = h12_codigo                 ";
    $sSql .= "        inner join rhferiasassenta on rh131_assenta = h16_codigo                 ";
    $sSql .= "        inner join portariaproced on h40_sequencial = h30_portariaproced         ";
    $sSql .= "where h16_regist         = {$iCodigoServidor}                                    ";
    $sSql .= "  and h30_tipoasse       = {$iTipoAssentamento}                                  ";
    $sSql .= "  and ( h16_dtconc, h16_dtlanc ) overlaps ( '{$sDataInicial}', '{$sDataFinal}' ) ";

    if( !empty($iSequencialAssentamento) ){
      $sSql .= " and h16_codigo <> {$iSequencialAssentamento}                                  ";      
    }

    return $sSql;
   }

  function sql_query_assentamento_com_substituicao($h16_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

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
     $sql .= " from assenta ";
     $sql .= " left join assentamentosubstituicao on h16_codigo = rh161_assentamento
              inner join tipoasse on h16_assent = h12_codigo
              inner join naturezatipoassentamento on h12_natureza = rh159_sequencial
               left join assentaloteregistroponto on h16_codigo = rh160_assentamento
               left join loteregistroponto on rh160_loteregistroponto = rh155_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($h16_codigo!=null ){
         $sql2 .= " where assenta.h16_codigo = $h16_codigo "; 
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

  function sql_query_servidores_com_assentamento_substituicao($h16_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

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
     $sql .= " from assenta ";
     $sql .= " inner join assentamentosubstituicao on h16_codigo = rh161_assentamento
              inner join tipoasse on h16_assent = h12_codigo
              inner join naturezatipoassentamento on h12_natureza = rh159_sequencial
               left join assentaloteregistroponto on h16_codigo = rh160_assentamento
               left join loteregistroponto on rh160_loteregistroponto = rh155_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($h16_codigo!=null ){
         $sql2 .= " where assenta.h16_codigo = $h16_codigo "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     $sql .= " group by h16_regist";
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

  function sql_query_funcional ( $h16_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

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
    $sql .= " from assenta ";
    $sql .= "      inner join db_usuarios            on id_usuario              = h16_login";
    $sql .= "      inner join tipoasse               on h12_codigo              = h16_assent";
    $sql .= "      inner join rhpessoal              on rh01_regist             = h16_regist";
    $sql .= "      inner join rhpessoalmov           on rh02_anousu             = ".db_anofolha()."
                                                    and rh02_mesusu             = ".db_mesfolha()."
                                                    and rh02_regist             = rh01_regist
                                                    and rh02_instit             = ".db_getsession('DB_instit');
    $sql .= "      inner join cgm                    on cgm.z01_numcgm          = rh01_numcgm";
    $sql .= "      inner join db_config              on codigo                  = rh01_instit";
    $sql .= "      inner join rhestcivil             on rhestcivil.rh08_estciv  = rh01_estciv";
    $sql .= "      inner join rhraca                 on rh18_raca               = rh01_raca";
    $sql .= "      inner join rhfuncao               on rhfuncao.rh37_funcao    = rh01_funcao
                                                    and rh37_instit             = ".db_getsession('DB_instit');
    $sql .= "      inner join rhinstrucao            on rhinstrucao.rh21_instru = rh01_instru";
    $sql .= "      inner join rhnacionalidade        on rh06_nacionalidade      = rh01_nacion";
    $sql .= "      left  join portariatipo           on h30_tipoasse            = h12_codigo";
    $sql .= "      left  join assentamentofuncional  on h16_codigo              = assentamentofuncional.rh193_assentamento_funcional";
    $sql2 = "";
    if($dbwhere==""){
      if($h16_codigo!=null ){
        $sql2 .= " where assenta.h16_codigo = $h16_codigo ";
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