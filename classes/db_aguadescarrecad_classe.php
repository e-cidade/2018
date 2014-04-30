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

//MODULO: agua
//CLASSE DA ENTIDADE aguadescarrecad
class cl_aguadescarrecad { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $x35_numpre = 0; 
   var $x35_numpar = 0; 
   var $x35_numcgm = 0; 
   var $x35_dtoper_dia = null; 
   var $x35_dtoper_mes = null; 
   var $x35_dtoper_ano = null; 
   var $x35_dtoper = null; 
   var $x35_receit = 0; 
   var $x35_hist = 0; 
   var $x35_valor = 0; 
   var $x35_dtvenc_dia = null; 
   var $x35_dtvenc_mes = null; 
   var $x35_dtvenc_ano = null; 
   var $x35_dtvenc = null; 
   var $x35_numtot = 0; 
   var $x35_numdig = 0; 
   var $x35_tipo = 0; 
   var $x35_tipojm = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 x35_numpre = int4 = Numpre 
                 x35_numpar = int4 = Numpar 
                 x35_numcgm = int4 = cgm 
                 x35_dtoper = date = Data Operação 
                 x35_receit = int4 = Receita 
                 x35_hist = int4 = Histórico 
                 x35_valor = float8 = Valor Débito 
                 x35_dtvenc = date = Data Vencimento 
                 x35_numtot = int4 = Total de Parcelas 
                 x35_numdig = int4 = digito 
                 x35_tipo = int4 = Tipo de débito 
                 x35_tipojm = int4 = Tipo de juro e multa 
                 ";
   //funcao construtor da classe 
   function cl_aguadescarrecad() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("aguadescarrecad"); 
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
       $this->x35_numpre = ($this->x35_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["x35_numpre"]:$this->x35_numpre);
       $this->x35_numpar = ($this->x35_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["x35_numpar"]:$this->x35_numpar);
       $this->x35_numcgm = ($this->x35_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["x35_numcgm"]:$this->x35_numcgm);
       if($this->x35_dtoper == ""){
         $this->x35_dtoper_dia = @$GLOBALS["HTTP_POST_VARS"]["x35_dtoper_dia"];
         $this->x35_dtoper_mes = @$GLOBALS["HTTP_POST_VARS"]["x35_dtoper_mes"];
         $this->x35_dtoper_ano = @$GLOBALS["HTTP_POST_VARS"]["x35_dtoper_ano"];
         if($this->x35_dtoper_dia != ""){
            $this->x35_dtoper = $this->x35_dtoper_ano."-".$this->x35_dtoper_mes."-".$this->x35_dtoper_dia;
         }
       }
       $this->x35_receit = ($this->x35_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["x35_receit"]:$this->x35_receit);
       $this->x35_hist = ($this->x35_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["x35_hist"]:$this->x35_hist);
       $this->x35_valor = ($this->x35_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["x35_valor"]:$this->x35_valor);
       if($this->x35_dtvenc == ""){
         $this->x35_dtvenc_dia = @$GLOBALS["HTTP_POST_VARS"]["x35_dtvenc_dia"];
         $this->x35_dtvenc_mes = @$GLOBALS["HTTP_POST_VARS"]["x35_dtvenc_mes"];
         $this->x35_dtvenc_ano = @$GLOBALS["HTTP_POST_VARS"]["x35_dtvenc_ano"];
         if($this->x35_dtvenc_dia != ""){
            $this->x35_dtvenc = $this->x35_dtvenc_ano."-".$this->x35_dtvenc_mes."-".$this->x35_dtvenc_dia;
         }
       }
       $this->x35_numtot = ($this->x35_numtot == ""?@$GLOBALS["HTTP_POST_VARS"]["x35_numtot"]:$this->x35_numtot);
       $this->x35_numdig = ($this->x35_numdig == ""?@$GLOBALS["HTTP_POST_VARS"]["x35_numdig"]:$this->x35_numdig);
       $this->x35_tipo = ($this->x35_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["x35_tipo"]:$this->x35_tipo);
       $this->x35_tipojm = ($this->x35_tipojm == ""?@$GLOBALS["HTTP_POST_VARS"]["x35_tipojm"]:$this->x35_tipojm);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->x35_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "x35_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x35_numpar == null ){ 
       $this->erro_sql = " Campo Numpar nao Informado.";
       $this->erro_campo = "x35_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x35_numcgm == null ){ 
       $this->erro_sql = " Campo cgm nao Informado.";
       $this->erro_campo = "x35_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x35_dtoper == null ){ 
       $this->erro_sql = " Campo Data Operação nao Informado.";
       $this->erro_campo = "x35_dtoper_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x35_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "x35_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x35_hist == null ){ 
       $this->erro_sql = " Campo Histórico nao Informado.";
       $this->erro_campo = "x35_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x35_valor == null ){ 
       $this->erro_sql = " Campo Valor Débito nao Informado.";
       $this->erro_campo = "x35_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x35_dtvenc == null ){ 
       $this->erro_sql = " Campo Data Vencimento nao Informado.";
       $this->erro_campo = "x35_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x35_numtot == null ){ 
       $this->erro_sql = " Campo Total de Parcelas nao Informado.";
       $this->erro_campo = "x35_numtot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x35_numdig == null ){ 
       $this->erro_sql = " Campo digito nao Informado.";
       $this->erro_campo = "x35_numdig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x35_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de débito nao Informado.";
       $this->erro_campo = "x35_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->x35_tipojm == null ){ 
       $this->erro_sql = " Campo Tipo de juro e multa nao Informado.";
       $this->erro_campo = "x35_tipojm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $result = @pg_query("insert into aguadescarrecad(
                                       x35_numpre 
                                      ,x35_numpar 
                                      ,x35_numcgm 
                                      ,x35_dtoper 
                                      ,x35_receit 
                                      ,x35_hist 
                                      ,x35_valor 
                                      ,x35_dtvenc 
                                      ,x35_numtot 
                                      ,x35_numdig 
                                      ,x35_tipo 
                                      ,x35_tipojm 
                       )
                values (
                                $this->x35_numpre 
                               ,$this->x35_numpar 
                               ,$this->x35_numcgm 
                               ,".($this->x35_dtoper == "null" || $this->x35_dtoper == ""?"null":"'".$this->x35_dtoper."'")." 
                               ,$this->x35_receit 
                               ,$this->x35_hist 
                               ,$this->x35_valor 
                               ,".($this->x35_dtvenc == "null" || $this->x35_dtvenc == ""?"null":"'".$this->x35_dtvenc."'")." 
                               ,$this->x35_numtot 
                               ,$this->x35_numdig 
                               ,$this->x35_tipo 
                               ,$this->x35_tipojm 
                      )");
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "aguadescarrecad () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "aguadescarrecad já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "aguadescarrecad () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     return true;
   } 
   // funcao para alteracao
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update aguadescarrecad set ";
     $virgula = "";
     if(trim($this->x35_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x35_numpre"])){ 
        if(trim($this->x35_numpre)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x35_numpre"])){ 
           $this->x35_numpre = "0" ; 
        } 
       $sql  .= $virgula." x35_numpre = $this->x35_numpre ";
       $virgula = ",";
       if(trim($this->x35_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "x35_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x35_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x35_numpar"])){ 
        if(trim($this->x35_numpar)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x35_numpar"])){ 
           $this->x35_numpar = "0" ; 
        } 
       $sql  .= $virgula." x35_numpar = $this->x35_numpar ";
       $virgula = ",";
       if(trim($this->x35_numpar) == null ){ 
         $this->erro_sql = " Campo Numpar nao Informado.";
         $this->erro_campo = "x35_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x35_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x35_numcgm"])){ 
        if(trim($this->x35_numcgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x35_numcgm"])){ 
           $this->x35_numcgm = "0" ; 
        } 
       $sql  .= $virgula." x35_numcgm = $this->x35_numcgm ";
       $virgula = ",";
       if(trim($this->x35_numcgm) == null ){ 
         $this->erro_sql = " Campo cgm nao Informado.";
         $this->erro_campo = "x35_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x35_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x35_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x35_dtoper_dia"] !="") ){ 
       $sql  .= $virgula." x35_dtoper = '$this->x35_dtoper' ";
       $virgula = ",";
       if(trim($this->x35_dtoper) == null ){ 
         $this->erro_sql = " Campo Data Operação nao Informado.";
         $this->erro_campo = "x35_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x35_dtoper_dia"])){ 
         $sql  .= $virgula." x35_dtoper = null ";
         $virgula = ",";
         if(trim($this->x35_dtoper) == null ){ 
           $this->erro_sql = " Campo Data Operação nao Informado.";
           $this->erro_campo = "x35_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x35_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x35_receit"])){ 
        if(trim($this->x35_receit)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x35_receit"])){ 
           $this->x35_receit = "0" ; 
        } 
       $sql  .= $virgula." x35_receit = $this->x35_receit ";
       $virgula = ",";
       if(trim($this->x35_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "x35_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x35_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x35_hist"])){ 
        if(trim($this->x35_hist)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x35_hist"])){ 
           $this->x35_hist = "0" ; 
        } 
       $sql  .= $virgula." x35_hist = $this->x35_hist ";
       $virgula = ",";
       if(trim($this->x35_hist) == null ){ 
         $this->erro_sql = " Campo Histórico nao Informado.";
         $this->erro_campo = "x35_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x35_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x35_valor"])){ 
        if(trim($this->x35_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x35_valor"])){ 
           $this->x35_valor = "0" ; 
        } 
       $sql  .= $virgula." x35_valor = $this->x35_valor ";
       $virgula = ",";
       if(trim($this->x35_valor) == null ){ 
         $this->erro_sql = " Campo Valor Débito nao Informado.";
         $this->erro_campo = "x35_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x35_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x35_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["x35_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." x35_dtvenc = '$this->x35_dtvenc' ";
       $virgula = ",";
       if(trim($this->x35_dtvenc) == null ){ 
         $this->erro_sql = " Campo Data Vencimento nao Informado.";
         $this->erro_campo = "x35_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["x35_dtvenc_dia"])){ 
         $sql  .= $virgula." x35_dtvenc = null ";
         $virgula = ",";
         if(trim($this->x35_dtvenc) == null ){ 
           $this->erro_sql = " Campo Data Vencimento nao Informado.";
           $this->erro_campo = "x35_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->x35_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x35_numtot"])){ 
        if(trim($this->x35_numtot)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x35_numtot"])){ 
           $this->x35_numtot = "0" ; 
        } 
       $sql  .= $virgula." x35_numtot = $this->x35_numtot ";
       $virgula = ",";
       if(trim($this->x35_numtot) == null ){ 
         $this->erro_sql = " Campo Total de Parcelas nao Informado.";
         $this->erro_campo = "x35_numtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x35_numdig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x35_numdig"])){ 
        if(trim($this->x35_numdig)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x35_numdig"])){ 
           $this->x35_numdig = "0" ; 
        } 
       $sql  .= $virgula." x35_numdig = $this->x35_numdig ";
       $virgula = ",";
       if(trim($this->x35_numdig) == null ){ 
         $this->erro_sql = " Campo digito nao Informado.";
         $this->erro_campo = "x35_numdig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x35_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x35_tipo"])){ 
        if(trim($this->x35_tipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x35_tipo"])){ 
           $this->x35_tipo = "0" ; 
        } 
       $sql  .= $virgula." x35_tipo = $this->x35_tipo ";
       $virgula = ",";
       if(trim($this->x35_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de débito nao Informado.";
         $this->erro_campo = "x35_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->x35_tipojm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["x35_tipojm"])){ 
        if(trim($this->x35_tipojm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["x35_tipojm"])){ 
           $this->x35_tipojm = "0" ; 
        } 
       $sql  .= $virgula." x35_tipojm = $this->x35_tipojm ";
       $virgula = ",";
       if(trim($this->x35_tipojm) == null ){ 
         $this->erro_sql = " Campo Tipo de juro e multa nao Informado.";
         $this->erro_campo = "x35_tipojm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where oid = $oid ";
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguadescarrecad nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguadescarrecad nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ( $oid=null ) { 
     $this->atualizacampos(true);
     $sql = " delete from aguadescarrecad
                    where ";
     $sql2 = "";
     $sql2 = "oid = $oid";
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "aguadescarrecad nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "aguadescarrecad nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n ".$this->erro_sql." \n\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $oid = null,$campos="aguadescarrecad.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from aguadescarrecad ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = aguadescarrecad.x35_numcgm";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = aguadescarrecad.x35_hist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = aguadescarrecad.x35_receit";
     $sql .= "      inner join arretipo  on  arretipo.k00_tipo = aguadescarrecad.x35_tipo";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join tabrectipo  on  tabrectipo.k116_sequencial = tabrec.k02_tabrectipo";
     $sql .= "      inner join db_config  on  db_config.codigo = arretipo.k00_instit";
     $sql .= "      inner join cadtipo  on  cadtipo.k03_tipo = arretipo.k03_tipo";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where aguadescarrecad.oid = $oid";
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
     $sql .= " from aguadescarrecad ";
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
}
?>