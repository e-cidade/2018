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
//CLASSE DA ENTIDADE assmeio
class cl_assmeio { 
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
   var $h22_codigo = 0; 
   var $h22_regist = 0; 
   var $h22_assent = 0; 
   var $h22_dtconc_dia = null; 
   var $h22_dtconc_mes = null; 
   var $h22_dtconc_ano = null; 
   var $h22_dtconc = null; 
   var $h22_histor = null; 
   var $h22_hist2 = null; 
   var $h22_nrport = null; 
   var $h22_atofic = null; 
   var $h22_quant = 0; 
   var $h22_perc = 0; 
   var $h22_dtterm_dia = null; 
   var $h22_dtterm_mes = null; 
   var $h22_dtterm_ano = null; 
   var $h22_dtterm = null; 
   var $h22_login = 0; 
   var $h22_dtlanc_dia = null; 
   var $h22_dtlanc_mes = null; 
   var $h22_dtlanc_ano = null; 
   var $h22_dtlanc = null; 
   var $h22_data_dia = null; 
   var $h22_data_mes = null; 
   var $h22_data_ano = null; 
   var $h22_data = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 h22_codigo = int4 = Código do Assentamento 
                 h22_regist = int4 = Codigo do Funcionario 
                 h22_assent = int4 = Código 
                 h22_dtconc = date = Data da Concessao do Assentame 
                 h22_histor = varchar(240) = Histórico 
                 h22_hist2 = varchar(240) = Histórico 2 
                 h22_nrport = varchar(6) = Portaria 
                 h22_atofic = varchar(15) = Descrição 
                 h22_quant = int4 = Qtda concedida 
                 h22_perc = float8 = Percentual concedido 
                 h22_dtterm = date = Data de termino do assentament 
                 h22_login = int4 = Login 
                 h22_dtlanc = date = Data do Lancamento 
                 h22_data = date = Data do segundo meio dia 
                 ";
   //funcao construtor da classe 
   function cl_assmeio() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("assmeio"); 
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
       $this->h22_codigo = ($this->h22_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_codigo"]:$this->h22_codigo);
       $this->h22_regist = ($this->h22_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_regist"]:$this->h22_regist);
       $this->h22_assent = ($this->h22_assent == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_assent"]:$this->h22_assent);
       if($this->h22_dtconc == ""){
         $this->h22_dtconc_dia = ($this->h22_dtconc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_dtconc_dia"]:$this->h22_dtconc_dia);
         $this->h22_dtconc_mes = ($this->h22_dtconc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_dtconc_mes"]:$this->h22_dtconc_mes);
         $this->h22_dtconc_ano = ($this->h22_dtconc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_dtconc_ano"]:$this->h22_dtconc_ano);
         if($this->h22_dtconc_dia != ""){
            $this->h22_dtconc = $this->h22_dtconc_ano."-".$this->h22_dtconc_mes."-".$this->h22_dtconc_dia;
         }
       }
       $this->h22_histor = ($this->h22_histor == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_histor"]:$this->h22_histor);
       $this->h22_hist2 = ($this->h22_hist2 == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_hist2"]:$this->h22_hist2);
       $this->h22_nrport = ($this->h22_nrport == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_nrport"]:$this->h22_nrport);
       $this->h22_atofic = ($this->h22_atofic == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_atofic"]:$this->h22_atofic);
       $this->h22_quant = ($this->h22_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_quant"]:$this->h22_quant);
       $this->h22_perc = ($this->h22_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_perc"]:$this->h22_perc);
       if($this->h22_dtterm == ""){
         $this->h22_dtterm_dia = ($this->h22_dtterm_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_dtterm_dia"]:$this->h22_dtterm_dia);
         $this->h22_dtterm_mes = ($this->h22_dtterm_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_dtterm_mes"]:$this->h22_dtterm_mes);
         $this->h22_dtterm_ano = ($this->h22_dtterm_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_dtterm_ano"]:$this->h22_dtterm_ano);
         if($this->h22_dtterm_dia != ""){
            $this->h22_dtterm = $this->h22_dtterm_ano."-".$this->h22_dtterm_mes."-".$this->h22_dtterm_dia;
         }
       }
       $this->h22_login = ($this->h22_login == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_login"]:$this->h22_login);
       if($this->h22_dtlanc == ""){
         $this->h22_dtlanc_dia = ($this->h22_dtlanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_dtlanc_dia"]:$this->h22_dtlanc_dia);
         $this->h22_dtlanc_mes = ($this->h22_dtlanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_dtlanc_mes"]:$this->h22_dtlanc_mes);
         $this->h22_dtlanc_ano = ($this->h22_dtlanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_dtlanc_ano"]:$this->h22_dtlanc_ano);
         if($this->h22_dtlanc_dia != ""){
            $this->h22_dtlanc = $this->h22_dtlanc_ano."-".$this->h22_dtlanc_mes."-".$this->h22_dtlanc_dia;
         }
       }
       if($this->h22_data == ""){
         $this->h22_data_dia = ($this->h22_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_data_dia"]:$this->h22_data_dia);
         $this->h22_data_mes = ($this->h22_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_data_mes"]:$this->h22_data_mes);
         $this->h22_data_ano = ($this->h22_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_data_ano"]:$this->h22_data_ano);
         if($this->h22_data_dia != ""){
            $this->h22_data = $this->h22_data_ano."-".$this->h22_data_mes."-".$this->h22_data_dia;
         }
       }
     }else{
       $this->h22_codigo = ($this->h22_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["h22_codigo"]:$this->h22_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($h22_codigo){ 
      $this->atualizacampos();
     if($this->h22_regist == null ){ 
       $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
       $this->erro_campo = "h22_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h22_assent == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "h22_assent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h22_dtconc == null ){ 
       $this->erro_sql = " Campo Data da Concessao do Assentame nao Informado.";
       $this->erro_campo = "h22_dtconc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h22_quant == null ){ 
       $this->erro_sql = " Campo Qtda concedida nao Informado.";
       $this->erro_campo = "h22_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h22_perc == null ){ 
       $this->erro_sql = " Campo Percentual concedido nao Informado.";
       $this->erro_campo = "h22_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h22_dtterm == null ){ 
       $this->erro_sql = " Campo Data de termino do assentament nao Informado.";
       $this->erro_campo = "h22_dtterm_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h22_login == null ){ 
       $this->erro_sql = " Campo Login nao Informado.";
       $this->erro_campo = "h22_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h22_dtlanc == null ){ 
       $this->erro_sql = " Campo Data do Lancamento nao Informado.";
       $this->erro_campo = "h22_dtlanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->h22_data == null ){ 
       $this->h22_data = "null";
     }
     if($h22_codigo == "" || $h22_codigo == null ){
       $result = db_query("select nextval('assmeio_h22_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: assmeio_h22_codigo_seq do campo: h22_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->h22_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from assmeio_h22_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $h22_codigo)){
         $this->erro_sql = " Campo h22_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->h22_codigo = $h22_codigo; 
       }
     }
     if(($this->h22_codigo == null) || ($this->h22_codigo == "") ){ 
       $this->erro_sql = " Campo h22_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into assmeio(
                                       h22_codigo 
                                      ,h22_regist 
                                      ,h22_assent 
                                      ,h22_dtconc 
                                      ,h22_histor 
                                      ,h22_hist2 
                                      ,h22_nrport 
                                      ,h22_atofic 
                                      ,h22_quant 
                                      ,h22_perc 
                                      ,h22_dtterm 
                                      ,h22_login 
                                      ,h22_dtlanc 
                                      ,h22_data 
                       )
                values (
                                $this->h22_codigo 
                               ,$this->h22_regist 
                               ,$this->h22_assent 
                               ,".($this->h22_dtconc == "null" || $this->h22_dtconc == ""?"null":"'".$this->h22_dtconc."'")." 
                               ,'$this->h22_histor' 
                               ,'$this->h22_hist2' 
                               ,'$this->h22_nrport' 
                               ,'$this->h22_atofic' 
                               ,$this->h22_quant 
                               ,$this->h22_perc 
                               ,".($this->h22_dtterm == "null" || $this->h22_dtterm == ""?"null":"'".$this->h22_dtterm."'")." 
                               ,$this->h22_login 
                               ,".($this->h22_dtlanc == "null" || $this->h22_dtlanc == ""?"null":"'".$this->h22_dtlanc."'")." 
                               ,".($this->h22_data == "null" || $this->h22_data == ""?"null":"'".$this->h22_data."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Assentamentos de meio dia                          ($this->h22_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Assentamentos de meio dia                          já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Assentamentos de meio dia                          ($this->h22_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h22_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->h22_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9554,'$this->h22_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,529,9554,'','".AddSlashes(pg_result($resaco,0,'h22_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,529,3673,'','".AddSlashes(pg_result($resaco,0,'h22_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,529,3674,'','".AddSlashes(pg_result($resaco,0,'h22_assent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,529,3675,'','".AddSlashes(pg_result($resaco,0,'h22_dtconc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,529,3676,'','".AddSlashes(pg_result($resaco,0,'h22_histor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,529,3677,'','".AddSlashes(pg_result($resaco,0,'h22_hist2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,529,3678,'','".AddSlashes(pg_result($resaco,0,'h22_nrport'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,529,3679,'','".AddSlashes(pg_result($resaco,0,'h22_atofic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,529,3680,'','".AddSlashes(pg_result($resaco,0,'h22_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,529,3681,'','".AddSlashes(pg_result($resaco,0,'h22_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,529,3682,'','".AddSlashes(pg_result($resaco,0,'h22_dtterm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,529,3683,'','".AddSlashes(pg_result($resaco,0,'h22_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,529,3684,'','".AddSlashes(pg_result($resaco,0,'h22_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,529,3685,'','".AddSlashes(pg_result($resaco,0,'h22_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($h22_codigo=null) { 
      $this->atualizacampos();
     $sql = " update assmeio set ";
     $virgula = "";
     if(trim($this->h22_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_codigo"])){ 
       $sql  .= $virgula." h22_codigo = $this->h22_codigo ";
       $virgula = ",";
       if(trim($this->h22_codigo) == null ){ 
         $this->erro_sql = " Campo Código do Assentamento nao Informado.";
         $this->erro_campo = "h22_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h22_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_regist"])){ 
       $sql  .= $virgula." h22_regist = $this->h22_regist ";
       $virgula = ",";
       if(trim($this->h22_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "h22_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h22_assent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_assent"])){ 
       $sql  .= $virgula." h22_assent = $this->h22_assent ";
       $virgula = ",";
       if(trim($this->h22_assent) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "h22_assent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h22_dtconc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_dtconc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h22_dtconc_dia"] !="") ){ 
       $sql  .= $virgula." h22_dtconc = '$this->h22_dtconc' ";
       $virgula = ",";
       if(trim($this->h22_dtconc) == null ){ 
         $this->erro_sql = " Campo Data da Concessao do Assentame nao Informado.";
         $this->erro_campo = "h22_dtconc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h22_dtconc_dia"])){ 
         $sql  .= $virgula." h22_dtconc = null ";
         $virgula = ",";
         if(trim($this->h22_dtconc) == null ){ 
           $this->erro_sql = " Campo Data da Concessao do Assentame nao Informado.";
           $this->erro_campo = "h22_dtconc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h22_histor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_histor"])){ 
       $sql  .= $virgula." h22_histor = '$this->h22_histor' ";
       $virgula = ",";
     }
     if(trim($this->h22_hist2)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_hist2"])){ 
       $sql  .= $virgula." h22_hist2 = '$this->h22_hist2' ";
       $virgula = ",";
     }
     if(trim($this->h22_nrport)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_nrport"])){ 
       $sql  .= $virgula." h22_nrport = '$this->h22_nrport' ";
       $virgula = ",";
     }
     if(trim($this->h22_atofic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_atofic"])){ 
       $sql  .= $virgula." h22_atofic = '$this->h22_atofic' ";
       $virgula = ",";
     }
     if(trim($this->h22_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_quant"])){ 
       $sql  .= $virgula." h22_quant = $this->h22_quant ";
       $virgula = ",";
       if(trim($this->h22_quant) == null ){ 
         $this->erro_sql = " Campo Qtda concedida nao Informado.";
         $this->erro_campo = "h22_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h22_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_perc"])){ 
       $sql  .= $virgula." h22_perc = $this->h22_perc ";
       $virgula = ",";
       if(trim($this->h22_perc) == null ){ 
         $this->erro_sql = " Campo Percentual concedido nao Informado.";
         $this->erro_campo = "h22_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h22_dtterm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_dtterm_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h22_dtterm_dia"] !="") ){ 
       $sql  .= $virgula." h22_dtterm = '$this->h22_dtterm' ";
       $virgula = ",";
       if(trim($this->h22_dtterm) == null ){ 
         $this->erro_sql = " Campo Data de termino do assentament nao Informado.";
         $this->erro_campo = "h22_dtterm_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h22_dtterm_dia"])){ 
         $sql  .= $virgula." h22_dtterm = null ";
         $virgula = ",";
         if(trim($this->h22_dtterm) == null ){ 
           $this->erro_sql = " Campo Data de termino do assentament nao Informado.";
           $this->erro_campo = "h22_dtterm_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h22_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_login"])){ 
       $sql  .= $virgula." h22_login = $this->h22_login ";
       $virgula = ",";
       if(trim($this->h22_login) == null ){ 
         $this->erro_sql = " Campo Login nao Informado.";
         $this->erro_campo = "h22_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->h22_dtlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_dtlanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h22_dtlanc_dia"] !="") ){ 
       $sql  .= $virgula." h22_dtlanc = '$this->h22_dtlanc' ";
       $virgula = ",";
       if(trim($this->h22_dtlanc) == null ){ 
         $this->erro_sql = " Campo Data do Lancamento nao Informado.";
         $this->erro_campo = "h22_dtlanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h22_dtlanc_dia"])){ 
         $sql  .= $virgula." h22_dtlanc = null ";
         $virgula = ",";
         if(trim($this->h22_dtlanc) == null ){ 
           $this->erro_sql = " Campo Data do Lancamento nao Informado.";
           $this->erro_campo = "h22_dtlanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->h22_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["h22_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["h22_data_dia"] !="") ){ 
       $sql  .= $virgula." h22_data = '$this->h22_data' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["h22_data_dia"])){ 
         $sql  .= $virgula." h22_data = null ";
         $virgula = ",";
       }
     }
     $sql .= " where ";
     if($h22_codigo!=null){
       $sql .= " h22_codigo = $this->h22_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->h22_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9554,'$this->h22_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_codigo"]))
           $resac = db_query("insert into db_acount values($acount,529,9554,'".AddSlashes(pg_result($resaco,$conresaco,'h22_codigo'))."','$this->h22_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_regist"]))
           $resac = db_query("insert into db_acount values($acount,529,3673,'".AddSlashes(pg_result($resaco,$conresaco,'h22_regist'))."','$this->h22_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_assent"]))
           $resac = db_query("insert into db_acount values($acount,529,3674,'".AddSlashes(pg_result($resaco,$conresaco,'h22_assent'))."','$this->h22_assent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_dtconc"]))
           $resac = db_query("insert into db_acount values($acount,529,3675,'".AddSlashes(pg_result($resaco,$conresaco,'h22_dtconc'))."','$this->h22_dtconc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_histor"]))
           $resac = db_query("insert into db_acount values($acount,529,3676,'".AddSlashes(pg_result($resaco,$conresaco,'h22_histor'))."','$this->h22_histor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_hist2"]))
           $resac = db_query("insert into db_acount values($acount,529,3677,'".AddSlashes(pg_result($resaco,$conresaco,'h22_hist2'))."','$this->h22_hist2',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_nrport"]))
           $resac = db_query("insert into db_acount values($acount,529,3678,'".AddSlashes(pg_result($resaco,$conresaco,'h22_nrport'))."','$this->h22_nrport',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_atofic"]))
           $resac = db_query("insert into db_acount values($acount,529,3679,'".AddSlashes(pg_result($resaco,$conresaco,'h22_atofic'))."','$this->h22_atofic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_quant"]))
           $resac = db_query("insert into db_acount values($acount,529,3680,'".AddSlashes(pg_result($resaco,$conresaco,'h22_quant'))."','$this->h22_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_perc"]))
           $resac = db_query("insert into db_acount values($acount,529,3681,'".AddSlashes(pg_result($resaco,$conresaco,'h22_perc'))."','$this->h22_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_dtterm"]))
           $resac = db_query("insert into db_acount values($acount,529,3682,'".AddSlashes(pg_result($resaco,$conresaco,'h22_dtterm'))."','$this->h22_dtterm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_login"]))
           $resac = db_query("insert into db_acount values($acount,529,3683,'".AddSlashes(pg_result($resaco,$conresaco,'h22_login'))."','$this->h22_login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_dtlanc"]))
           $resac = db_query("insert into db_acount values($acount,529,3684,'".AddSlashes(pg_result($resaco,$conresaco,'h22_dtlanc'))."','$this->h22_dtlanc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["h22_data"]))
           $resac = db_query("insert into db_acount values($acount,529,3685,'".AddSlashes(pg_result($resaco,$conresaco,'h22_data'))."','$this->h22_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Assentamentos de meio dia                          nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->h22_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Assentamentos de meio dia                          nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->h22_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->h22_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($h22_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($h22_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9554,'$h22_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,529,9554,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,529,3673,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,529,3674,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_assent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,529,3675,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_dtconc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,529,3676,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_histor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,529,3677,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_hist2'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,529,3678,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_nrport'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,529,3679,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_atofic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,529,3680,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,529,3681,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,529,3682,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_dtterm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,529,3683,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,529,3684,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_dtlanc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,529,3685,'','".AddSlashes(pg_result($resaco,$iresaco,'h22_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from assmeio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($h22_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " h22_codigo = $h22_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Assentamentos de meio dia                          nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$h22_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Assentamentos de meio dia                          nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$h22_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$h22_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:assmeio";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $h22_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from assmeio ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = assmeio.h22_login";
     $sql .= "      inner join tipoasse  on  tipoasse.h12_codigo = assmeio.h22_assent";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = assmeio.h22_regist";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = rhpessoal.rh01_instit";
     $sql .= "      inner join rhestcivil  on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv";
     $sql .= "      inner join rhraca  on  rhraca.rh18_raca = rhpessoal.rh01_raca";
     $sql .= "      inner join rhfuncao  on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao";
     $sql .= "      inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru";
     $sql .= "      inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion";
     $sql2 = "";
     if($dbwhere==""){
       if($h22_codigo!=null ){
         $sql2 .= " where assmeio.h22_codigo = $h22_codigo "; 
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
   function sql_query_file ( $h22_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from assmeio ";
     $sql2 = "";
     if($dbwhere==""){
       if($h22_codigo!=null ){
         $sql2 .= " where assmeio.h22_codigo = $h22_codigo "; 
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