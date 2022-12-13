<?
/*
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

//MODULO: caixa
//CLASSE DA ENTIDADE arrecad
class cl_arrecad { 
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
   var $k00_numpre = 0; 
   var $k00_numpar = 0; 
   var $k00_numcgm = 0; 
   var $k00_dtoper_dia = null; 
   var $k00_dtoper_mes = null; 
   var $k00_dtoper_ano = null; 
   var $k00_dtoper = null; 
   var $k00_receit = 0; 
   var $k00_hist = 0; 
   var $k00_valor = 0; 
   var $k00_dtvenc_dia = null; 
   var $k00_dtvenc_mes = null; 
   var $k00_dtvenc_ano = null; 
   var $k00_dtvenc = null; 
   var $k00_numtot = 0; 
   var $k00_numdig = 0; 
   var $k00_tipo = 0; 
   var $k00_tipojm = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k00_numpre = int4 = Numpre 
                 k00_numpar = int4 = Parcela 
                 k00_numcgm = int4 = cgm 
                 k00_dtoper = date = DT.Lanc 
                 k00_receit = int4 = Receita 
                 k00_hist = int4 = Histórico de Cálculo 
                 k00_valor = float8 = Valor 
                 k00_dtvenc = date = DT.Venc 
                 k00_numtot = int4 = Tot 
                 k00_numdig = int4 = D 
                 k00_tipo = int4 = Tipo de Débito 
                 k00_tipojm = int4 = tipo de juro e multa 
                 ";
   //funcao construtor da classe 
   function cl_arrecad() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arrecad"); 
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
       $this->k00_numpre = ($this->k00_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpre"]:$this->k00_numpre);
       $this->k00_numpar = ($this->k00_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpar"]:$this->k00_numpar);
       $this->k00_numcgm = ($this->k00_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numcgm"]:$this->k00_numcgm);
       if($this->k00_dtoper == ""){
         $this->k00_dtoper_dia = ($this->k00_dtoper_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"]:$this->k00_dtoper_dia);
         $this->k00_dtoper_mes = ($this->k00_dtoper_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_mes"]:$this->k00_dtoper_mes);
         $this->k00_dtoper_ano = ($this->k00_dtoper_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtoper_ano"]:$this->k00_dtoper_ano);
         if($this->k00_dtoper_dia != ""){
            $this->k00_dtoper = $this->k00_dtoper_ano."-".$this->k00_dtoper_mes."-".$this->k00_dtoper_dia;
         }
       }
       $this->k00_receit = ($this->k00_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_receit"]:$this->k00_receit);
       $this->k00_hist = ($this->k00_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_hist"]:$this->k00_hist);
       $this->k00_valor = ($this->k00_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_valor"]:$this->k00_valor);
       if($this->k00_dtvenc == ""){
         $this->k00_dtvenc_dia = ($this->k00_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"]:$this->k00_dtvenc_dia);
         $this->k00_dtvenc_mes = ($this->k00_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_mes"]:$this->k00_dtvenc_mes);
         $this->k00_dtvenc_ano = ($this->k00_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_ano"]:$this->k00_dtvenc_ano);
         if($this->k00_dtvenc_dia != ""){
            $this->k00_dtvenc = $this->k00_dtvenc_ano."-".$this->k00_dtvenc_mes."-".$this->k00_dtvenc_dia;
         }
       }
       $this->k00_numtot = ($this->k00_numtot == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numtot"]:$this->k00_numtot);
       $this->k00_numdig = ($this->k00_numdig == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numdig"]:$this->k00_numdig);
       $this->k00_tipo = ($this->k00_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_tipo"]:$this->k00_tipo);
       $this->k00_tipojm = ($this->k00_tipojm == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_tipojm"]:$this->k00_tipojm);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->k00_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "k00_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numpar == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "k00_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numcgm == null ){ 
       $this->erro_sql = " Campo cgm nao Informado.";
       $this->erro_campo = "k00_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtoper == null ){ 
       $this->erro_sql = " Campo DT.Lanc nao Informado.";
       $this->erro_campo = "k00_dtoper_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "k00_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_hist == null ){ 
       $this->erro_sql = " Campo Histórico de Cálculo nao Informado.";
       $this->erro_campo = "k00_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "k00_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtvenc == null ){ 
       $this->erro_sql = " Campo DT.Venc nao Informado.";
       $this->erro_campo = "k00_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numtot == null ){ 
       $this->erro_sql = " Campo Tot nao Informado.";
       $this->erro_campo = "k00_numtot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_numdig == null ){ 
       $this->erro_sql = " Campo D nao Informado.";
       $this->erro_campo = "k00_numdig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Débito nao Informado.";
       $this->erro_campo = "k00_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_tipojm == null ){ 
       $this->erro_sql = " Campo tipo de juro e multa nao Informado.";
       $this->erro_campo = "k00_tipojm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arrecad(
                                       k00_numpre 
                                      ,k00_numpar 
                                      ,k00_numcgm 
                                      ,k00_dtoper 
                                      ,k00_receit 
                                      ,k00_hist 
                                      ,k00_valor 
                                      ,k00_dtvenc 
                                      ,k00_numtot 
                                      ,k00_numdig 
                                      ,k00_tipo 
                                      ,k00_tipojm 
                       )
                values (
                                $this->k00_numpre 
                               ,$this->k00_numpar 
                               ,$this->k00_numcgm 
                               ,".($this->k00_dtoper == "null" || $this->k00_dtoper == ""?"null":"'".$this->k00_dtoper."'")." 
                               ,$this->k00_receit 
                               ,$this->k00_hist 
                               ,$this->k00_valor 
                               ,".($this->k00_dtvenc == "null" || $this->k00_dtvenc == ""?"null":"'".$this->k00_dtvenc."'")." 
                               ,$this->k00_numtot 
                               ,$this->k00_numdig 
                               ,$this->k00_tipo 
                               ,$this->k00_tipojm 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null,$where="" ) { 
      $this->atualizacampos();
     $sql = " update arrecad set ";
     $virgula = "";
     if(trim($this->k00_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"])){ 
       $sql  .= $virgula." k00_numpre = $this->k00_numpre ";
       $virgula = ",";
       if(trim($this->k00_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k00_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpar"])){ 
       $sql  .= $virgula." k00_numpar = $this->k00_numpar ";
       $virgula = ",";
       if(trim($this->k00_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "k00_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numcgm"])){ 
       $sql  .= $virgula." k00_numcgm = $this->k00_numcgm ";
       $virgula = ",";
       if(trim($this->k00_numcgm) == null ){ 
         $this->erro_sql = " Campo cgm nao Informado.";
         $this->erro_campo = "k00_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"] !="") ){ 
       $sql  .= $virgula." k00_dtoper = '$this->k00_dtoper' ";
       $virgula = ",";
       if(trim($this->k00_dtoper) == null ){ 
         $this->erro_sql = " Campo DT.Lanc nao Informado.";
         $this->erro_campo = "k00_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"])){ 
         $sql  .= $virgula." k00_dtoper = null ";
         $virgula = ",";
         if(trim($this->k00_dtoper) == null ){ 
           $this->erro_sql = " Campo DT.Lanc nao Informado.";
           $this->erro_campo = "k00_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k00_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_receit"])){ 
       $sql  .= $virgula." k00_receit = $this->k00_receit ";
       $virgula = ",";
       if(trim($this->k00_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "k00_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist"])){ 
       $sql  .= $virgula." k00_hist = $this->k00_hist ";
       $virgula = ",";
       if(trim($this->k00_hist) == null ){ 
         $this->erro_sql = " Campo Histórico de Cálculo nao Informado.";
         $this->erro_campo = "k00_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_valor"])){ 
       $sql  .= $virgula." k00_valor = $this->k00_valor ";
       $virgula = ",";
       if(trim($this->k00_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "k00_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." k00_dtvenc = '$this->k00_dtvenc' ";
       $virgula = ",";
       if(trim($this->k00_dtvenc) == null ){ 
         $this->erro_sql = " Campo DT.Venc nao Informado.";
         $this->erro_campo = "k00_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"])){ 
         $sql  .= $virgula." k00_dtvenc = null ";
         $virgula = ",";
         if(trim($this->k00_dtvenc) == null ){ 
           $this->erro_sql = " Campo DT.Venc nao Informado.";
           $this->erro_campo = "k00_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k00_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numtot"])){ 
       $sql  .= $virgula." k00_numtot = $this->k00_numtot ";
       $virgula = ",";
       if(trim($this->k00_numtot) == null ){ 
         $this->erro_sql = " Campo Tot nao Informado.";
         $this->erro_campo = "k00_numtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numdig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numdig"])){ 
       $sql  .= $virgula." k00_numdig = $this->k00_numdig ";
       $virgula = ",";
       if(trim($this->k00_numdig) == null ){ 
         $this->erro_sql = " Campo D nao Informado.";
         $this->erro_campo = "k00_numdig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_tipo"])){ 
       $sql  .= $virgula." k00_tipo = $this->k00_tipo ";
       $virgula = ",";
       if(trim($this->k00_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Débito nao Informado.";
         $this->erro_campo = "k00_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_tipojm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_tipojm"])){ 
       $sql  .= $virgula." k00_tipojm = $this->k00_tipojm ";
       $virgula = ",";
       if(trim($this->k00_tipojm) == null ){ 
         $this->erro_sql = " Campo tipo de juro e multa nao Informado.";
         $this->erro_campo = "k00_tipojm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }

     if ($where != "") {
         $sql .= " where $where ";
     }

      $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ,$dbwhere=null) { 
     $sql = " delete from arrecad
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
       $sql2 = "oid = '$oid'";
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:arrecad";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function alterar_arrecad ( $dbwhere="" ) { 
      $this->atualizacampos();
     $sql = " update arrecad set ";
     $virgula = "";
     if(trim($this->k00_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpre"])){ 
       $sql  .= $virgula." k00_numpre = $this->k00_numpre ";
       $virgula = ",";
       if(trim($this->k00_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k00_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numpar"])){ 
       $sql  .= $virgula." k00_numpar = $this->k00_numpar ";
       $virgula = ",";
       if(trim($this->k00_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "k00_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numcgm"])){ 
       $sql  .= $virgula." k00_numcgm = $this->k00_numcgm ";
       $virgula = ",";
       if(trim($this->k00_numcgm) == null ){ 
         $this->erro_sql = " Campo cgm nao Informado.";
         $this->erro_campo = "k00_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"] !="") ){ 
       $sql  .= $virgula." k00_dtoper = '$this->k00_dtoper' ";
       $virgula = ",";
       if(trim($this->k00_dtoper) == null ){ 
         $this->erro_sql = " Campo DT.Lanc nao Informado.";
         $this->erro_campo = "k00_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtoper_dia"])){ 
         $sql  .= $virgula." k00_dtoper = null ";
         $virgula = ",";
         if(trim($this->k00_dtoper) == null ){ 
           $this->erro_sql = " Campo DT.Lanc nao Informado.";
           $this->erro_campo = "k00_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k00_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_receit"])){ 
       $sql  .= $virgula." k00_receit = $this->k00_receit ";
       $virgula = ",";
       if(trim($this->k00_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "k00_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_hist"])){ 
       $sql  .= $virgula." k00_hist = $this->k00_hist ";
       $virgula = ",";
       if(trim($this->k00_hist) == null ){ 
         $this->erro_sql = " Campo Hist.Calc. nao Informado.";
         $this->erro_campo = "k00_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_valor"])){ 
       $sql  .= $virgula." k00_valor = $this->k00_valor ";
       $virgula = ",";
       if(trim($this->k00_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "k00_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." k00_dtvenc = '$this->k00_dtvenc' ";
       $virgula = ",";
       if(trim($this->k00_dtvenc) == null ){ 
         $this->erro_sql = " Campo DT.Venc nao Informado.";
         $this->erro_campo = "k00_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtvenc_dia"])){ 
         $sql  .= $virgula." k00_dtvenc = null ";
         $virgula = ",";
         if(trim($this->k00_dtvenc) == null ){ 
           $this->erro_sql = " Campo DT.Venc nao Informado.";
           $this->erro_campo = "k00_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k00_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numtot"])){ 
       $sql  .= $virgula." k00_numtot = $this->k00_numtot ";
       $virgula = ",";
       if(trim($this->k00_numtot) == null ){ 
         $this->erro_sql = " Campo Tot nao Informado.";
         $this->erro_campo = "k00_numtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_numdig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numdig"])){ 
       $sql  .= $virgula." k00_numdig = $this->k00_numdig ";
       $virgula = ",";
       if(trim($this->k00_numdig) == null ){ 
         $this->erro_sql = " Campo D nao Informado.";
         $this->erro_campo = "k00_numdig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_tipo"])){ 
       $sql  .= $virgula." k00_tipo = $this->k00_tipo ";
       $virgula = ",";
       if(trim($this->k00_tipo) == null ){ 
         $this->erro_sql = " Campo tipo de debito nao Informado.";
         $this->erro_campo = "k00_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_tipojm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_tipojm"])){ 
       $sql  .= $virgula." k00_tipojm = $this->k00_tipojm ";
       $virgula = ",";
       if(trim($this->k00_tipojm) == null ){ 
         $this->erro_sql = " Campo tipo de juro e multa nao Informado.";
         $this->erro_campo = "k00_tipojm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if (isset($dbwhere)&&$dbwhere!=""){
       $sql .= " where  $dbwhere ";
     }
     $result = @db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alterao efetuada com Sucesso\\n";
         $this->erro_msg   = "Usurio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   }
   function excluir_arrecad($numpre,$numpar=0, $excluir_arrecad=true,$receit=null){
    $sql = "select *
            from arrecad
            where k00_numpre=$numpre";
    if($numpar>0){
      $sql .= " and k00_numpar = $numpar";
    }
    if($receit != null ){
    	$sql .= " and k00_receit = $receit";
    }
    $result=@db_query($sql);
    $numrows =  pg_numrows($result);
    if($result!=false && $numrows>0){
      $k00_numpre = pg_result($result,0,"k00_numpre");
      $k00_numpar = pg_result($result,0,"k00_numpar");
      $k00_numcgm = pg_result($result,0,"k00_numcgm");
      $k00_dtoper = pg_result($result,0,"k00_dtoper");
      $k00_receit = pg_result($result,0,"k00_receit");
      $k00_hist = pg_result($result,0,"k00_hist");
      $k00_valor = pg_result($result,0,"k00_valor");
      $k00_dtvenc = pg_result($result,0,"k00_dtvenc");
      $d00_numtot = pg_result($result,0,"k00_numtot");
      $k00_numdig = pg_result($result,0,"k00_numdig");
      $k00_tipo = pg_result($result,0,"k00_tipo");
      $k00_tipojm = pg_result($result,0,"k00_tipojm");
      for($a=0; $a<$numrows; $a++){
        $sql_in = "insert into arreold(
                                       k00_numpre
                                      ,k00_numpar
                                      ,k00_numcgm
                                      ,k00_dtoper
                                      ,k00_receit
                                      ,k00_hist
                                      ,k00_valor
                                      ,k00_dtvenc
                                      ,k00_numtot
                                      ,k00_numdig
                                      ,k00_tipo
                                      ,k00_tipojm
                       )
                values (
                                       $k00_numpre
                                      ,$k00_numpar
                                      ,$k00_numcgm
                                      ,'$k00_dtoper'
                                      ,$k00_receit
                                      ,$k00_hist
                                      ,$k00_valor
                                      ,'$k00_dtvenc'
                                      ,$d00_numtot
                                      ,".(int) $k00_numdig ."
                                      ,'$k00_tipo'
                                      ," . (int) $k00_tipojm . "
		      )
		";
        $result = @db_query($sql_in) or die($sql_in);
         if($result==false){	
            $this->erro_status="0";
            $this->erro_msg="Erro ao incluir em Arreold";
            //return false;
         }
       }
     }
     if($excluir_arrecad ==  true){
       $sql = "delete from arrecad where k00_numpre=$numpre";
       if($numpar>0){
         $sql .= " and k00_numpar = $numpar";
       }
       if($receit != null ){
    	$sql .= " and k00_receit = $receit";
     	}
       $result=@db_query($sql);
       if($result==false){
          $this->erro_status="0";
          $this->erro_msg="Erro ao excluir em Arrecad";
          //return false;
       }else{
	 $this->erro_status="1";
	 $this->erro_msg="Excluso efetivada com sucesso!";
	// return true;
       }
     }
  }
   function sql_query ( $oid = null,$campos="arrecad.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arrecad ";
		 $sql .= "      inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre";
     $sql .= "      inner join cgm        on  cgm.z01_numcgm = arrecad.k00_numcgm";
     $sql .= "      inner join histcalc   on  histcalc.k01_codigo = arrecad.k00_hist";
     $sql .= "      inner join tabrec     on  tabrec.k02_codigo = arrecad.k00_receit";
     $sql .= "      inner join arretipo   on  arretipo.k00_tipo = arrecad.k00_tipo";
     $sql .= "      inner join tabrecjm   on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join cadtipo    on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where arrecad.oid = '$oid'";
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
   function sql_query_div ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arrecad ";
		 $sql .= "      inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre";
     $sql .= "      inner join divida  on  arrecad.k00_numpre = divida.v01_numpre and arrecad.k00_numpar=divida.v01_numpar";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = arrecad.k00_tipo";
     $sql .="       inner join cadtipo on cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql2 = "";
     if($dbwhere==""){
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
   function sql_query_file ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arrecad ";
//		 $sql .= " inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre";
     $sql2 = "";
     if($dbwhere==""){
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
   function sql_query_file_instit ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= "arrecad.*";
     }
     $sql .= " from arrecad ";
		 $sql .= " inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre";
     $sql2 = "";
     if($dbwhere==""){
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
   function sql_query_info ( $oid = null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arrecad ";
		 $sql .= "      inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre " ;
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = arrecad.k00_tipo";
     $sql .="       inner join cadtipo on cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql .= "      left join arrenumcgm  on  arrenumcgm.k00_numpre = arrecad.k00_numpre";
     $sql .= "      left join arreinscr  on  arreinscr.k00_numpre = arrecad.k00_numpre";
     $sql .= "      left join arrematric  on  arrematric.k00_numpre = arrecad.k00_numpre";
     $sql2 = "";
     if($dbwhere==""){
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
   function sql_query_instit ( $oid = null,$campos="arrecad.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arrecad ";
		 $sql .= "      inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre";
     $sql .= "      inner join cgm        on  cgm.z01_numcgm = arrecad.k00_numcgm";
     $sql .= "      inner join histcalc   on  histcalc.k01_codigo = arrecad.k00_hist";
     $sql .= "      inner join tabrec     on  tabrec.k02_codigo = arrecad.k00_receit";
     $sql .= "      inner join arretipo   on  arretipo.k00_tipo = arrecad.k00_tipo";
     $sql .= "      inner join tabrecjm   on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join cadtipo    on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where arrecad.oid = '$oid'";
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
	  
	function excluir_arrecad_inc_arrecant($numpre,$numpar=0, $excluir_arrecad=true){
	    $sql = "select *
	            from arrecad
	            where k00_numpre=$numpre";
	    if($numpar>0){
	      $sql .= " and k00_numpar = $numpar";
	    }
	    $result=@db_query($sql);
	    $numrows =  pg_numrows($result);
	    if($result!=false && $numrows>0){
	      $k00_numpre = pg_result($result,0,"k00_numpre");
	      $k00_numpar = pg_result($result,0,"k00_numpar");
	      $k00_numcgm = pg_result($result,0,"k00_numcgm");
	      $k00_dtoper = pg_result($result,0,"k00_dtoper");
	      $k00_receit = pg_result($result,0,"k00_receit");
	      $k00_hist   = pg_result($result,0,"k00_hist");
	      $k00_valor = pg_result($result,0,"k00_valor");
	      $k00_dtvenc = pg_result($result,0,"k00_dtvenc");
	      $d00_numtot = pg_result($result,0,"k00_numtot");
	      $k00_numdig = pg_result($result,0,"k00_numdig");
	      $k00_tipo = pg_result($result,0,"k00_tipo");
	      $k00_tipojm = pg_result($result,0,"k00_tipojm");
	      for($a=0; $a<$numrows; $a++){
	        $sql_in = "insert into arrecant(
	                                       k00_numpre
	                                      ,k00_numpar
	                                      ,k00_numcgm
	                                      ,k00_dtoper
	                                      ,k00_receit
	                                      ,k00_hist
	                                      ,k00_valor
	                                      ,k00_dtvenc
	                                      ,k00_numtot
	                                      ,k00_numdig
	                                      ,k00_tipo
	                                      ,k00_tipojm
	                       )
	                values (
	                                       $k00_numpre
	                                      ,$k00_numpar
	                                      ,$k00_numcgm
	                                      ,'$k00_dtoper'
	                                      ,$k00_receit
	                                      ,$k00_hist
	                                      ,$k00_valor
	                                      ,'$k00_dtvenc'
	                                      ,$d00_numtot
	                                      ,$k00_numdig
	                                      ,'$k00_tipo'
	                                      ,'$k00_tipojm'
			      )
			";
	        $result = @db_query($sql_in);
	         if($result==false){	
	            $this->erro_status="0";
	            $this->erro_msg="Erro ao incluir em Arrecant";
	            //return false;
	         }
	       }
	     }
	     if($excluir_arrecad ==  true){
	       $sql = "delete from arrecad where k00_numpre=$numpre";
	       if($numpar>0){
	         $sql .= " and k00_numpar = $numpar";
	       }
	       $result=@db_query($sql);
	       if($result==false){
	          $this->erro_status="0";
	          $this->erro_msg="Erro ao excluir em Arrecad";
	          //return false;
	       }else{
		       $this->erro_status="1";
		       $this->erro_msg="Exclusão efetivada com sucesso!";
		       // return true;
	       }
	     }
	  }
	  
	/**
	 * Busca todos os descontos de um Numpre "x" da instituição de logoin
	 *
	 * @param integer $k00_numpre
	 * @return string
	 */
	
	function sql_query_buscaDesconto($k00_numpre) { 
	    
	  if ($k00_numpre != "" || $k00_numpre != null || isset($k00_numpre)){
	   
	    $sql  = "select distinct *                                                                                        "; 
	    $sql .= "  from ( select 'a' as tipo,                                                                             ";
	    $sql .= "                arrecad.k00_dtoper,                                                                      ";
	    $sql .= "                arrecad.k00_receit,                                                                      ";
	    $sql .= "                k02_drecei,                                                                              "; 
	    $sql .= "                abs(arrecad.k00_valor) as k00_valor,                                                     ";
	    $sql .= "                arrecad.oid,                                                                             ";
	    $sql .= "                k00_numpar,                                                                              ";
	    $sql .= "                null as k00_dtpaga                                                                       ";
	    $sql .= "           from arrecad                                                                                  ";
	    $sql .= "          inner join tabrec     on k00_receit            = k02_codigo                                    ";
	    $sql .= "          inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre                            ";
	    $sql .= "                               and arreinstit.k00_instit = ". db_getsession("DB_instit");
	    $sql .= "          where arrecad.k00_numpre = {$k00_numpre}                                                       ";
	    $sql .= "            and arrecad.k00_hist = 918                                                                   ";
	    $sql .= "       union                                                                                             ";
	    $sql .= "        select 'r' as tipo,                                                                              ";
	    $sql .= "               arrecant.k00_dtoper,                                                                      ";
	    $sql .= "               arrecant.k00_receit,                                                                      ";
	    $sql .= "               k02_drecei,                                                                               ";
	    $sql .= "               abs(arrecant.k00_valor) as k00_valor,                                                     ";
	    $sql .= "               arrecant.oid,                                                                             ";
	    $sql .= "               arrecant.k00_numpar,                                                                      ";
	    $sql .= "               arrepaga.k00_dtpaga                                                                       "; 
	    $sql .= "          from arrecant                                                                                  ";
	    $sql .= "         inner join tabrec         on k00_receit          = k02_codigo                                   ";
	    $sql .= "          left outer join arrepaga on arrecant.k00_numpre = arrepaga.k00_numpre                          ";
	    $sql .= "                                  and arrecant.k00_numpar = arrepaga.k00_numpar                          ";
	    $sql .= "          inner join arreinstit on arreinstit.k00_numpre      = arrecant.k00_numpre                      ";
	    $sql .= "                             and arreinstit.k00_instit      = ". db_getsession("DB_instit");
	    $sql .= "         where k00_dtpaga is null                                                                        ";
	    $sql .= "           and arrecant.k00_hist   = 918                                                                 ";
	    $sql .= "           and arrecant.k00_numpre = {$k00_numpre}                                                       "; 
	    $sql .= "       ) as retorno                                                                                      ";
	    $sql .= " order by k00_numpar                                                                                     "; 
	    
	  }
	  return $sql;
	}
	
	/**
	 * Busca todas as receitas de um tipo especifico
	 *
	 * @param integer $k03_tipo
	 * @return string
	 */
	function sql_query_getReceitasTipo($k03_tipo){
		
		$sSql = null;
		
		if (!empty($k03_tipo)){
			 
      $sSql  = "select distinct                                                                                                      \n";
      $sSql .= "       k02_descr,															                                                                       \n"; 
			$sSql .= "		   k00_receit															                                                                       \n";
			$sSql .= "  from arrecad																					                                                             \n";
			$sSql .= "       inner 	 join tabrec   on k02_codigo = k00_receit                                                              \n";
			$sSql .= "       natural join arretipo													                                                               \n";
			$sSql .= "       natural join cadtipo														                                                               \n";
			$sSql .= "       natural join arreinstit 												                                                               \n";
			$sSql .= " where cadtipo.k03_tipo 		  =  {$k03_tipo}                                                                         \n";
			$sSql .= " 	 and arreinstit.k00_instit = ".db_getsession("DB_instit");
			$sSql .= "   and not exists (select 1 from arrepaga where k00_numpre = arrecad.k00_numpre and k00_receit = arrecad.k00_receit) \n";
      $sSql .= "   and not exists (select 1 from arrecant where k00_numpre = arrecad.k00_numpre and k00_receit = arrecad.k00_receit) \n";
     	$sSql .= "   and not exists (select 1 from arreold  where k00_numpre = arrecad.k00_numpre and k00_receit = arrecad.k00_receit) \n";
      $sSql .= "   and not exists (select 1 from arresusp where k00_numpre = arrecad.k00_numpre and k00_receit = arrecad.k00_receit) \n";
		}
		return $sSql;
	}

}
?>