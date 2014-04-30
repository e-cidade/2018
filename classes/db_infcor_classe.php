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

//MODULO: inflatores
//CLASSE DA ENTIDADE infcor
class cl_infcor { 
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
   var $i04_codigo = 0; 
   var $i04_seq = 0; 
   var $i04_obs = null; 
   var $i04_dtoper_dia = null; 
   var $i04_dtoper_mes = null; 
   var $i04_dtoper_ano = null; 
   var $i04_dtoper = null; 
   var $i04_dtvenc_dia = null; 
   var $i04_dtvenc_mes = null; 
   var $i04_dtvenc_ano = null; 
   var $i04_dtvenc = null; 
   var $i04_valor = 0; 
   var $i04_receit = 0; 
   var $i04_correcao = 0; 
   var $i04_juros = 0; 
   var $i04_multa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 i04_codigo = int8 = Código 
                 i04_seq = int8 = Sequencia de Cadastro 
                 i04_obs = text = Observação 
                 i04_dtoper = date = Data Valor 
                 i04_dtvenc = date = Data Vencimento 
                 i04_valor = float8 = Valor 
                 i04_receit = int8 = Receita 
                 i04_correcao = float8 = Valor da correção 
                 i04_juros = float8 = Juros 
                 i04_multa = float8 = Multa 
                 ";
   //funcao construtor da classe 
   function cl_infcor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("infcor"); 
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
       $this->i04_codigo = ($this->i04_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_codigo"]:$this->i04_codigo);
       $this->i04_seq = ($this->i04_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_seq"]:$this->i04_seq);
       $this->i04_obs = ($this->i04_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_obs"]:$this->i04_obs);
       if($this->i04_dtoper == ""){
         $this->i04_dtoper_dia = ($this->i04_dtoper_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_dtoper_dia"]:$this->i04_dtoper_dia);
         $this->i04_dtoper_mes = ($this->i04_dtoper_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_dtoper_mes"]:$this->i04_dtoper_mes);
         $this->i04_dtoper_ano = ($this->i04_dtoper_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_dtoper_ano"]:$this->i04_dtoper_ano);
         if($this->i04_dtoper_dia != ""){
            $this->i04_dtoper = $this->i04_dtoper_ano."-".$this->i04_dtoper_mes."-".$this->i04_dtoper_dia;
         }
       }
       if($this->i04_dtvenc == ""){
         $this->i04_dtvenc_dia = ($this->i04_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_dtvenc_dia"]:$this->i04_dtvenc_dia);
         $this->i04_dtvenc_mes = ($this->i04_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_dtvenc_mes"]:$this->i04_dtvenc_mes);
         $this->i04_dtvenc_ano = ($this->i04_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_dtvenc_ano"]:$this->i04_dtvenc_ano);
         if($this->i04_dtvenc_dia != ""){
            $this->i04_dtvenc = $this->i04_dtvenc_ano."-".$this->i04_dtvenc_mes."-".$this->i04_dtvenc_dia;
         }
       }
       $this->i04_valor = ($this->i04_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_valor"]:$this->i04_valor);
       $this->i04_receit = ($this->i04_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_receit"]:$this->i04_receit);
       $this->i04_correcao = ($this->i04_correcao == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_correcao"]:$this->i04_correcao);
       $this->i04_juros = ($this->i04_juros == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_juros"]:$this->i04_juros);
       $this->i04_multa = ($this->i04_multa == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_multa"]:$this->i04_multa);
     }else{
       $this->i04_codigo = ($this->i04_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_codigo"]:$this->i04_codigo);
       $this->i04_seq = ($this->i04_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["i04_seq"]:$this->i04_seq);
     }
   }
   // funcao para inclusao
   function incluir ($i04_codigo,$i04_seq){ 
      $this->atualizacampos();
     if($this->i04_dtoper == null ){ 
       $this->erro_sql = " Campo Data Valor nao Informado.";
       $this->erro_campo = "i04_dtoper_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i04_dtvenc == null ){ 
       $this->erro_sql = " Campo Data Vencimento nao Informado.";
       $this->erro_campo = "i04_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i04_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "i04_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i04_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "i04_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i04_correcao == null ){ 
       $this->erro_sql = " Campo Valor da correção nao Informado.";
       $this->erro_campo = "i04_correcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i04_juros == null ){ 
       $this->erro_sql = " Campo Juros nao Informado.";
       $this->erro_campo = "i04_juros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->i04_multa == null ){ 
       $this->erro_sql = " Campo Multa nao Informado.";
       $this->erro_campo = "i04_multa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->i04_codigo = $i04_codigo; 
       $this->i04_seq = $i04_seq; 
     if(($this->i04_codigo == null) || ($this->i04_codigo == "") ){ 
       $this->erro_sql = " Campo i04_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->i04_seq == null) || ($this->i04_seq == "") ){ 
       $this->erro_sql = " Campo i04_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into infcor(
                                       i04_codigo 
                                      ,i04_seq 
                                      ,i04_obs 
                                      ,i04_dtoper 
                                      ,i04_dtvenc 
                                      ,i04_valor 
                                      ,i04_receit 
                                      ,i04_correcao 
                                      ,i04_juros 
                                      ,i04_multa 
                       )
                values (
                                $this->i04_codigo 
                               ,$this->i04_seq 
                               ,'$this->i04_obs' 
                               ,".($this->i04_dtoper == "null" || $this->i04_dtoper == ""?"null":"'".$this->i04_dtoper."'")." 
                               ,".($this->i04_dtvenc == "null" || $this->i04_dtvenc == ""?"null":"'".$this->i04_dtvenc."'")." 
                               ,$this->i04_valor 
                               ,$this->i04_receit 
                               ,$this->i04_correcao 
                               ,$this->i04_juros 
                               ,$this->i04_multa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Valores da Planilha de Atualização ($this->i04_codigo."-".$this->i04_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Valores da Planilha de Atualização já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Valores da Planilha de Atualização ($this->i04_codigo."-".$this->i04_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->i04_codigo."-".$this->i04_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->i04_codigo,$this->i04_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,2420,'$this->i04_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,2425,'$this->i04_seq','I')");
       $resac = db_query("insert into db_acount values($acount,392,2420,'','".AddSlashes(pg_result($resaco,0,'i04_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,392,2425,'','".AddSlashes(pg_result($resaco,0,'i04_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,392,2424,'','".AddSlashes(pg_result($resaco,0,'i04_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,392,2422,'','".AddSlashes(pg_result($resaco,0,'i04_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,392,2421,'','".AddSlashes(pg_result($resaco,0,'i04_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,392,2423,'','".AddSlashes(pg_result($resaco,0,'i04_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,392,2417,'','".AddSlashes(pg_result($resaco,0,'i04_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,392,4846,'','".AddSlashes(pg_result($resaco,0,'i04_correcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,392,4847,'','".AddSlashes(pg_result($resaco,0,'i04_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,392,4848,'','".AddSlashes(pg_result($resaco,0,'i04_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($i04_codigo=null,$i04_seq=null) { 
      $this->atualizacampos();
     $sql = " update infcor set ";
     $virgula = "";
     if(trim($this->i04_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i04_codigo"])){ 
       $sql  .= $virgula." i04_codigo = $this->i04_codigo ";
       $virgula = ",";
       if(trim($this->i04_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "i04_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i04_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i04_seq"])){ 
       $sql  .= $virgula." i04_seq = $this->i04_seq ";
       $virgula = ",";
       if(trim($this->i04_seq) == null ){ 
         $this->erro_sql = " Campo Sequencia de Cadastro nao Informado.";
         $this->erro_campo = "i04_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i04_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i04_obs"])){ 
       $sql  .= $virgula." i04_obs = '$this->i04_obs' ";
       $virgula = ",";
     }
     if(trim($this->i04_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i04_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["i04_dtoper_dia"] !="") ){ 
       $sql  .= $virgula." i04_dtoper = '$this->i04_dtoper' ";
       $virgula = ",";
       if(trim($this->i04_dtoper) == null ){ 
         $this->erro_sql = " Campo Data Valor nao Informado.";
         $this->erro_campo = "i04_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["i04_dtoper_dia"])){ 
         $sql  .= $virgula." i04_dtoper = null ";
         $virgula = ",";
         if(trim($this->i04_dtoper) == null ){ 
           $this->erro_sql = " Campo Data Valor nao Informado.";
           $this->erro_campo = "i04_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->i04_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i04_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["i04_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." i04_dtvenc = '$this->i04_dtvenc' ";
       $virgula = ",";
       if(trim($this->i04_dtvenc) == null ){ 
         $this->erro_sql = " Campo Data Vencimento nao Informado.";
         $this->erro_campo = "i04_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["i04_dtvenc_dia"])){ 
         $sql  .= $virgula." i04_dtvenc = null ";
         $virgula = ",";
         if(trim($this->i04_dtvenc) == null ){ 
           $this->erro_sql = " Campo Data Vencimento nao Informado.";
           $this->erro_campo = "i04_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->i04_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i04_valor"])){ 
       $sql  .= $virgula." i04_valor = $this->i04_valor ";
       $virgula = ",";
       if(trim($this->i04_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "i04_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i04_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i04_receit"])){ 
       $sql  .= $virgula." i04_receit = $this->i04_receit ";
       $virgula = ",";
       if(trim($this->i04_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "i04_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i04_correcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i04_correcao"])){ 
       $sql  .= $virgula." i04_correcao = $this->i04_correcao ";
       $virgula = ",";
       if(trim($this->i04_correcao) == null ){ 
         $this->erro_sql = " Campo Valor da correção nao Informado.";
         $this->erro_campo = "i04_correcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i04_juros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i04_juros"])){ 
       $sql  .= $virgula." i04_juros = $this->i04_juros ";
       $virgula = ",";
       if(trim($this->i04_juros) == null ){ 
         $this->erro_sql = " Campo Juros nao Informado.";
         $this->erro_campo = "i04_juros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->i04_multa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["i04_multa"])){ 
       $sql  .= $virgula." i04_multa = $this->i04_multa ";
       $virgula = ",";
       if(trim($this->i04_multa) == null ){ 
         $this->erro_sql = " Campo Multa nao Informado.";
         $this->erro_campo = "i04_multa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($i04_codigo!=null){
       $sql .= " i04_codigo = $this->i04_codigo";
     }
     if($i04_seq!=null){
       $sql .= " and  i04_seq = $this->i04_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->i04_codigo,$this->i04_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2420,'$this->i04_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,2425,'$this->i04_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i04_codigo"]))
           $resac = db_query("insert into db_acount values($acount,392,2420,'".AddSlashes(pg_result($resaco,$conresaco,'i04_codigo'))."','$this->i04_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i04_seq"]))
           $resac = db_query("insert into db_acount values($acount,392,2425,'".AddSlashes(pg_result($resaco,$conresaco,'i04_seq'))."','$this->i04_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i04_obs"]))
           $resac = db_query("insert into db_acount values($acount,392,2424,'".AddSlashes(pg_result($resaco,$conresaco,'i04_obs'))."','$this->i04_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i04_dtoper"]))
           $resac = db_query("insert into db_acount values($acount,392,2422,'".AddSlashes(pg_result($resaco,$conresaco,'i04_dtoper'))."','$this->i04_dtoper',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i04_dtvenc"]))
           $resac = db_query("insert into db_acount values($acount,392,2421,'".AddSlashes(pg_result($resaco,$conresaco,'i04_dtvenc'))."','$this->i04_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i04_valor"]))
           $resac = db_query("insert into db_acount values($acount,392,2423,'".AddSlashes(pg_result($resaco,$conresaco,'i04_valor'))."','$this->i04_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i04_receit"]))
           $resac = db_query("insert into db_acount values($acount,392,2417,'".AddSlashes(pg_result($resaco,$conresaco,'i04_receit'))."','$this->i04_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i04_correcao"]))
           $resac = db_query("insert into db_acount values($acount,392,4846,'".AddSlashes(pg_result($resaco,$conresaco,'i04_correcao'))."','$this->i04_correcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i04_juros"]))
           $resac = db_query("insert into db_acount values($acount,392,4847,'".AddSlashes(pg_result($resaco,$conresaco,'i04_juros'))."','$this->i04_juros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["i04_multa"]))
           $resac = db_query("insert into db_acount values($acount,392,4848,'".AddSlashes(pg_result($resaco,$conresaco,'i04_multa'))."','$this->i04_multa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores da Planilha de Atualização nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->i04_codigo."-".$this->i04_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores da Planilha de Atualização nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->i04_codigo."-".$this->i04_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->i04_codigo."-".$this->i04_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($i04_codigo=null,$i04_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($i04_codigo,$i04_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,2420,'$i04_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,2425,'$i04_seq','E')");
         $resac = db_query("insert into db_acount values($acount,392,2420,'','".AddSlashes(pg_result($resaco,$iresaco,'i04_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,392,2425,'','".AddSlashes(pg_result($resaco,$iresaco,'i04_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,392,2424,'','".AddSlashes(pg_result($resaco,$iresaco,'i04_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,392,2422,'','".AddSlashes(pg_result($resaco,$iresaco,'i04_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,392,2421,'','".AddSlashes(pg_result($resaco,$iresaco,'i04_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,392,2423,'','".AddSlashes(pg_result($resaco,$iresaco,'i04_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,392,2417,'','".AddSlashes(pg_result($resaco,$iresaco,'i04_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,392,4846,'','".AddSlashes(pg_result($resaco,$iresaco,'i04_correcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,392,4847,'','".AddSlashes(pg_result($resaco,$iresaco,'i04_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,392,4848,'','".AddSlashes(pg_result($resaco,$iresaco,'i04_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from infcor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($i04_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " i04_codigo = $i04_codigo ";
        }
        if($i04_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " i04_seq = $i04_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Valores da Planilha de Atualização nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$i04_codigo."-".$i04_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Valores da Planilha de Atualização nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$i04_codigo."-".$i04_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$i04_codigo."-".$i04_seq;
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
        $this->erro_sql   = "Record Vazio na Tabela:infcor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $i04_codigo=null,$i04_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from infcor ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = infcor.i04_receit";
     $sql .= "      inner join infcab  on  infcab.i03_codigo = infcor.i04_codigo";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = infcab.i03_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = infcab.i03_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($i04_codigo!=null ){
         $sql2 .= " where infcor.i04_codigo = $i04_codigo "; 
       } 
       if($i04_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " infcor.i04_seq = $i04_seq "; 
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
   function sql_query_file ( $i04_codigo=null,$i04_seq=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from infcor ";
     $sql2 = "";
     if($dbwhere==""){
       if($i04_codigo!=null ){
         $sql2 .= " where infcor.i04_codigo = $i04_codigo "; 
       } 
       if($i04_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " infcor.i04_seq = $i04_seq "; 
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