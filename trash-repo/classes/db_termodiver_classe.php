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

//MODULO: dividaativa
//CLASSE DA ENTIDADE termodiver
class cl_termodiver { 
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
   var $dv10_parcel = 0; 
   var $dv10_coddiver = 0; 
   var $dv10_valor = 0; 
   var $dv10_juros = 0; 
   var $dv10_multa = 0; 
   var $dv10_desconto = 0; 
   var $dv10_total = 0; 
   var $dv10_numpreant = 0; 
   var $dv10_perc = 0; 
   var $dv10_vlrcor = 0; 
   var $dv10_vlrdescjur = 0; 
   var $dv10_vlrdescmul = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 dv10_parcel = int4 = Código do Parcelamento 
                 dv10_coddiver = int4 = Código do diversos 
                 dv10_valor = float8 = Valor 
                 dv10_juros = float8 = Juros 
                 dv10_multa = float8 = Multa 
                 dv10_desconto = float8 = Desconto 
                 dv10_total = float8 = Total 
                 dv10_numpreant = int4 = Numpre anterior 
                 dv10_perc = float8 = Percentual 
                 dv10_vlrcor = float8 = Valor corrigido 
                 dv10_vlrdescjur = float8 = Valor Desconto Juros 
                 dv10_vlrdescmul = float8 = Valor Desconto Multa 
                 ";
   //funcao construtor da classe 
   function cl_termodiver() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("termodiver"); 
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
       $this->dv10_parcel = ($this->dv10_parcel == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_parcel"]:$this->dv10_parcel);
       $this->dv10_coddiver = ($this->dv10_coddiver == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_coddiver"]:$this->dv10_coddiver);
       $this->dv10_valor = ($this->dv10_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_valor"]:$this->dv10_valor);
       $this->dv10_juros = ($this->dv10_juros == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_juros"]:$this->dv10_juros);
       $this->dv10_multa = ($this->dv10_multa == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_multa"]:$this->dv10_multa);
       $this->dv10_desconto = ($this->dv10_desconto == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_desconto"]:$this->dv10_desconto);
       $this->dv10_total = ($this->dv10_total == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_total"]:$this->dv10_total);
       $this->dv10_numpreant = ($this->dv10_numpreant == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_numpreant"]:$this->dv10_numpreant);
       $this->dv10_perc = ($this->dv10_perc == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_perc"]:$this->dv10_perc);
       $this->dv10_vlrcor = ($this->dv10_vlrcor == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_vlrcor"]:$this->dv10_vlrcor);
       $this->dv10_vlrdescjur = ($this->dv10_vlrdescjur == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_vlrdescjur"]:$this->dv10_vlrdescjur);
       $this->dv10_vlrdescmul = ($this->dv10_vlrdescmul == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_vlrdescmul"]:$this->dv10_vlrdescmul);
     }else{
       $this->dv10_parcel = ($this->dv10_parcel == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_parcel"]:$this->dv10_parcel);
       $this->dv10_coddiver = ($this->dv10_coddiver == ""?@$GLOBALS["HTTP_POST_VARS"]["dv10_coddiver"]:$this->dv10_coddiver);
     }
   }
   // funcao para inclusao
   function incluir ($dv10_parcel,$dv10_coddiver){ 
      $this->atualizacampos();
     if($this->dv10_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "dv10_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv10_juros == null ){ 
       $this->erro_sql = " Campo Juros nao Informado.";
       $this->erro_campo = "dv10_juros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv10_multa == null ){ 
       $this->erro_sql = " Campo Multa nao Informado.";
       $this->erro_campo = "dv10_multa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv10_desconto == null ){ 
       $this->erro_sql = " Campo Desconto nao Informado.";
       $this->erro_campo = "dv10_desconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv10_total == null ){ 
       $this->erro_sql = " Campo Total nao Informado.";
       $this->erro_campo = "dv10_total";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv10_numpreant == null ){ 
       $this->erro_sql = " Campo Numpre anterior nao Informado.";
       $this->erro_campo = "dv10_numpreant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv10_perc == null ){ 
       $this->erro_sql = " Campo Percentual nao Informado.";
       $this->erro_campo = "dv10_perc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv10_vlrcor == null ){ 
       $this->erro_sql = " Campo Valor corrigido nao Informado.";
       $this->erro_campo = "dv10_vlrcor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv10_vlrdescjur == null ){ 
       $this->erro_sql = " Campo Valor Desconto Juros nao Informado.";
       $this->erro_campo = "dv10_vlrdescjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dv10_vlrdescmul == null ){ 
       $this->erro_sql = " Campo Valor Desconto Multa nao Informado.";
       $this->erro_campo = "dv10_vlrdescmul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->dv10_parcel = $dv10_parcel; 
       $this->dv10_coddiver = $dv10_coddiver; 
     if(($this->dv10_parcel == null) || ($this->dv10_parcel == "") ){ 
       $this->erro_sql = " Campo dv10_parcel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->dv10_coddiver == null) || ($this->dv10_coddiver == "") ){ 
       $this->erro_sql = " Campo dv10_coddiver nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into termodiver(
                                       dv10_parcel 
                                      ,dv10_coddiver 
                                      ,dv10_valor 
                                      ,dv10_juros 
                                      ,dv10_multa 
                                      ,dv10_desconto 
                                      ,dv10_total 
                                      ,dv10_numpreant 
                                      ,dv10_perc 
                                      ,dv10_vlrcor 
                                      ,dv10_vlrdescjur 
                                      ,dv10_vlrdescmul 
                       )
                values (
                                $this->dv10_parcel 
                               ,$this->dv10_coddiver 
                               ,$this->dv10_valor 
                               ,$this->dv10_juros 
                               ,$this->dv10_multa 
                               ,$this->dv10_desconto 
                               ,$this->dv10_total 
                               ,$this->dv10_numpreant 
                               ,$this->dv10_perc 
                               ,$this->dv10_vlrcor 
                               ,$this->dv10_vlrdescjur 
                               ,$this->dv10_vlrdescmul 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Diversos do parcelamento ($this->dv10_parcel."-".$this->dv10_coddiver) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Diversos do parcelamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Diversos do parcelamento ($this->dv10_parcel."-".$this->dv10_coddiver) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv10_parcel."-".$this->dv10_coddiver;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->dv10_parcel,$this->dv10_coddiver));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3605,'$this->dv10_parcel','I')");
       $resac = db_query("insert into db_acountkey values($acount,3606,'$this->dv10_coddiver','I')");
       $resac = db_query("insert into db_acount values($acount,523,3605,'','".AddSlashes(pg_result($resaco,0,'dv10_parcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,523,3606,'','".AddSlashes(pg_result($resaco,0,'dv10_coddiver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,523,3607,'','".AddSlashes(pg_result($resaco,0,'dv10_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,523,3608,'','".AddSlashes(pg_result($resaco,0,'dv10_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,523,3609,'','".AddSlashes(pg_result($resaco,0,'dv10_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,523,3610,'','".AddSlashes(pg_result($resaco,0,'dv10_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,523,3611,'','".AddSlashes(pg_result($resaco,0,'dv10_total'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,523,3612,'','".AddSlashes(pg_result($resaco,0,'dv10_numpreant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,523,3613,'','".AddSlashes(pg_result($resaco,0,'dv10_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,523,7218,'','".AddSlashes(pg_result($resaco,0,'dv10_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,523,9141,'','".AddSlashes(pg_result($resaco,0,'dv10_vlrdescjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,523,9142,'','".AddSlashes(pg_result($resaco,0,'dv10_vlrdescmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($dv10_parcel=null,$dv10_coddiver=null) { 
      $this->atualizacampos();
     $sql = " update termodiver set ";
     $virgula = "";
     if(trim($this->dv10_parcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv10_parcel"])){ 
       $sql  .= $virgula." dv10_parcel = $this->dv10_parcel ";
       $virgula = ",";
       if(trim($this->dv10_parcel) == null ){ 
         $this->erro_sql = " Campo Código do Parcelamento nao Informado.";
         $this->erro_campo = "dv10_parcel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv10_coddiver)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv10_coddiver"])){ 
       $sql  .= $virgula." dv10_coddiver = $this->dv10_coddiver ";
       $virgula = ",";
       if(trim($this->dv10_coddiver) == null ){ 
         $this->erro_sql = " Campo Código do diversos nao Informado.";
         $this->erro_campo = "dv10_coddiver";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv10_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv10_valor"])){ 
       $sql  .= $virgula." dv10_valor = $this->dv10_valor ";
       $virgula = ",";
       if(trim($this->dv10_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "dv10_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv10_juros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv10_juros"])){ 
       $sql  .= $virgula." dv10_juros = $this->dv10_juros ";
       $virgula = ",";
       if(trim($this->dv10_juros) == null ){ 
         $this->erro_sql = " Campo Juros nao Informado.";
         $this->erro_campo = "dv10_juros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv10_multa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv10_multa"])){ 
       $sql  .= $virgula." dv10_multa = $this->dv10_multa ";
       $virgula = ",";
       if(trim($this->dv10_multa) == null ){ 
         $this->erro_sql = " Campo Multa nao Informado.";
         $this->erro_campo = "dv10_multa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv10_desconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv10_desconto"])){ 
       $sql  .= $virgula." dv10_desconto = $this->dv10_desconto ";
       $virgula = ",";
       if(trim($this->dv10_desconto) == null ){ 
         $this->erro_sql = " Campo Desconto nao Informado.";
         $this->erro_campo = "dv10_desconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv10_total)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv10_total"])){ 
       $sql  .= $virgula." dv10_total = $this->dv10_total ";
       $virgula = ",";
       if(trim($this->dv10_total) == null ){ 
         $this->erro_sql = " Campo Total nao Informado.";
         $this->erro_campo = "dv10_total";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv10_numpreant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv10_numpreant"])){ 
       $sql  .= $virgula." dv10_numpreant = $this->dv10_numpreant ";
       $virgula = ",";
       if(trim($this->dv10_numpreant) == null ){ 
         $this->erro_sql = " Campo Numpre anterior nao Informado.";
         $this->erro_campo = "dv10_numpreant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv10_perc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv10_perc"])){ 
       $sql  .= $virgula." dv10_perc = $this->dv10_perc ";
       $virgula = ",";
       if(trim($this->dv10_perc) == null ){ 
         $this->erro_sql = " Campo Percentual nao Informado.";
         $this->erro_campo = "dv10_perc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv10_vlrcor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv10_vlrcor"])){ 
       $sql  .= $virgula." dv10_vlrcor = $this->dv10_vlrcor ";
       $virgula = ",";
       if(trim($this->dv10_vlrcor) == null ){ 
         $this->erro_sql = " Campo Valor corrigido nao Informado.";
         $this->erro_campo = "dv10_vlrcor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv10_vlrdescjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv10_vlrdescjur"])){ 
       $sql  .= $virgula." dv10_vlrdescjur = $this->dv10_vlrdescjur ";
       $virgula = ",";
       if(trim($this->dv10_vlrdescjur) == null ){ 
         $this->erro_sql = " Campo Valor Desconto Juros nao Informado.";
         $this->erro_campo = "dv10_vlrdescjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dv10_vlrdescmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dv10_vlrdescmul"])){ 
       $sql  .= $virgula." dv10_vlrdescmul = $this->dv10_vlrdescmul ";
       $virgula = ",";
       if(trim($this->dv10_vlrdescmul) == null ){ 
         $this->erro_sql = " Campo Valor Desconto Multa nao Informado.";
         $this->erro_campo = "dv10_vlrdescmul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($dv10_parcel!=null){
       $sql .= " dv10_parcel = $this->dv10_parcel";
     }
     if($dv10_coddiver!=null){
       $sql .= " and  dv10_coddiver = $this->dv10_coddiver";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->dv10_parcel,$this->dv10_coddiver));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3605,'$this->dv10_parcel','A')");
         $resac = db_query("insert into db_acountkey values($acount,3606,'$this->dv10_coddiver','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv10_parcel"]))
           $resac = db_query("insert into db_acount values($acount,523,3605,'".AddSlashes(pg_result($resaco,$conresaco,'dv10_parcel'))."','$this->dv10_parcel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv10_coddiver"]))
           $resac = db_query("insert into db_acount values($acount,523,3606,'".AddSlashes(pg_result($resaco,$conresaco,'dv10_coddiver'))."','$this->dv10_coddiver',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv10_valor"]))
           $resac = db_query("insert into db_acount values($acount,523,3607,'".AddSlashes(pg_result($resaco,$conresaco,'dv10_valor'))."','$this->dv10_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv10_juros"]))
           $resac = db_query("insert into db_acount values($acount,523,3608,'".AddSlashes(pg_result($resaco,$conresaco,'dv10_juros'))."','$this->dv10_juros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv10_multa"]))
           $resac = db_query("insert into db_acount values($acount,523,3609,'".AddSlashes(pg_result($resaco,$conresaco,'dv10_multa'))."','$this->dv10_multa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv10_desconto"]))
           $resac = db_query("insert into db_acount values($acount,523,3610,'".AddSlashes(pg_result($resaco,$conresaco,'dv10_desconto'))."','$this->dv10_desconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv10_total"]))
           $resac = db_query("insert into db_acount values($acount,523,3611,'".AddSlashes(pg_result($resaco,$conresaco,'dv10_total'))."','$this->dv10_total',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv10_numpreant"]))
           $resac = db_query("insert into db_acount values($acount,523,3612,'".AddSlashes(pg_result($resaco,$conresaco,'dv10_numpreant'))."','$this->dv10_numpreant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv10_perc"]))
           $resac = db_query("insert into db_acount values($acount,523,3613,'".AddSlashes(pg_result($resaco,$conresaco,'dv10_perc'))."','$this->dv10_perc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv10_vlrcor"]))
           $resac = db_query("insert into db_acount values($acount,523,7218,'".AddSlashes(pg_result($resaco,$conresaco,'dv10_vlrcor'))."','$this->dv10_vlrcor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv10_vlrdescjur"]))
           $resac = db_query("insert into db_acount values($acount,523,9141,'".AddSlashes(pg_result($resaco,$conresaco,'dv10_vlrdescjur'))."','$this->dv10_vlrdescjur',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dv10_vlrdescmul"]))
           $resac = db_query("insert into db_acount values($acount,523,9142,'".AddSlashes(pg_result($resaco,$conresaco,'dv10_vlrdescmul'))."','$this->dv10_vlrdescmul',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diversos do parcelamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->dv10_parcel."-".$this->dv10_coddiver;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Diversos do parcelamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->dv10_parcel."-".$this->dv10_coddiver;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->dv10_parcel."-".$this->dv10_coddiver;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($dv10_parcel=null,$dv10_coddiver=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($dv10_parcel,$dv10_coddiver));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3605,'$dv10_parcel','E')");
         $resac = db_query("insert into db_acountkey values($acount,3606,'$dv10_coddiver','E')");
         $resac = db_query("insert into db_acount values($acount,523,3605,'','".AddSlashes(pg_result($resaco,$iresaco,'dv10_parcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,523,3606,'','".AddSlashes(pg_result($resaco,$iresaco,'dv10_coddiver'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,523,3607,'','".AddSlashes(pg_result($resaco,$iresaco,'dv10_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,523,3608,'','".AddSlashes(pg_result($resaco,$iresaco,'dv10_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,523,3609,'','".AddSlashes(pg_result($resaco,$iresaco,'dv10_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,523,3610,'','".AddSlashes(pg_result($resaco,$iresaco,'dv10_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,523,3611,'','".AddSlashes(pg_result($resaco,$iresaco,'dv10_total'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,523,3612,'','".AddSlashes(pg_result($resaco,$iresaco,'dv10_numpreant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,523,3613,'','".AddSlashes(pg_result($resaco,$iresaco,'dv10_perc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,523,7218,'','".AddSlashes(pg_result($resaco,$iresaco,'dv10_vlrcor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,523,9141,'','".AddSlashes(pg_result($resaco,$iresaco,'dv10_vlrdescjur'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,523,9142,'','".AddSlashes(pg_result($resaco,$iresaco,'dv10_vlrdescmul'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from termodiver
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($dv10_parcel != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " dv10_parcel = $dv10_parcel ";
        }
        if($dv10_coddiver != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " dv10_coddiver = $dv10_coddiver ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diversos do parcelamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$dv10_parcel."-".$dv10_coddiver;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Diversos do parcelamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$dv10_parcel."-".$dv10_coddiver;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$dv10_parcel."-".$dv10_coddiver;
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
        $this->erro_sql   = "Record Vazio na Tabela:termodiver";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>