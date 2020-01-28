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

//MODULO: protocolo
//CLASSE DA ENTIDADE recibocanc
class cl_recibocanc { 
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
   var $p99_numcgm = 0; 
   var $p99_dtoper_dia = null; 
   var $p99_dtoper_mes = null; 
   var $p99_dtoper_ano = null; 
   var $p99_dtoper = null; 
   var $p99_receit = 0; 
   var $p99_hist = 0; 
   var $p99_valor = 0; 
   var $p99_dtvenc_dia = null; 
   var $p99_dtvenc_mes = null; 
   var $p99_dtvenc_ano = null; 
   var $p99_dtvenc = null; 
   var $p99_numpre = 0; 
   var $p99_numpar = 0; 
   var $p99_numtot = 0; 
   var $p99_numdig = 0; 
   var $p99_tipo = 0; 
   var $p99_tipojm = 0; 
   var $p99_numnov = 0; 
   var $p99_codsubrec = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p99_numcgm = int4 = Numcgm 
                 p99_dtoper = date = Data 
                 p99_receit = int4 = Receita 
                 p99_hist = int4 = Historico 
                 p99_valor = float4 = Valor 
                 p99_dtvenc = date = Vencimento 
                 p99_numpre = int4 = Numpre 
                 p99_numpar = int4 = Parcela 
                 p99_numtot = int4 = Total 
                 p99_numdig = int4 = Numdig 
                 p99_tipo = int4 = Tipo 
                 p99_tipojm = int4 = Tipojm 
                 p99_numnov = float4 = Numnov 
                 p99_codsubrec = int4 = codsubrec 
                 ";
   //funcao construtor da classe 
   function cl_recibocanc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("recibocanc"); 
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
       $this->p99_numcgm = ($this->p99_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_numcgm"]:$this->p99_numcgm);
       if($this->p99_dtoper == ""){
         $this->p99_dtoper_dia = ($this->p99_dtoper_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_dtoper_dia"]:$this->p99_dtoper_dia);
         $this->p99_dtoper_mes = ($this->p99_dtoper_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_dtoper_mes"]:$this->p99_dtoper_mes);
         $this->p99_dtoper_ano = ($this->p99_dtoper_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_dtoper_ano"]:$this->p99_dtoper_ano);
         if($this->p99_dtoper_dia != ""){
            $this->p99_dtoper = $this->p99_dtoper_ano."-".$this->p99_dtoper_mes."-".$this->p99_dtoper_dia;
         }
       }
       $this->p99_receit = ($this->p99_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_receit"]:$this->p99_receit);
       $this->p99_hist = ($this->p99_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_hist"]:$this->p99_hist);
       $this->p99_valor = ($this->p99_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_valor"]:$this->p99_valor);
       if($this->p99_dtvenc == ""){
         $this->p99_dtvenc_dia = ($this->p99_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_dtvenc_dia"]:$this->p99_dtvenc_dia);
         $this->p99_dtvenc_mes = ($this->p99_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_dtvenc_mes"]:$this->p99_dtvenc_mes);
         $this->p99_dtvenc_ano = ($this->p99_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_dtvenc_ano"]:$this->p99_dtvenc_ano);
         if($this->p99_dtvenc_dia != ""){
            $this->p99_dtvenc = $this->p99_dtvenc_ano."-".$this->p99_dtvenc_mes."-".$this->p99_dtvenc_dia;
         }
       }
       $this->p99_numpre = ($this->p99_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_numpre"]:$this->p99_numpre);
       $this->p99_numpar = ($this->p99_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_numpar"]:$this->p99_numpar);
       $this->p99_numtot = ($this->p99_numtot == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_numtot"]:$this->p99_numtot);
       $this->p99_numdig = ($this->p99_numdig == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_numdig"]:$this->p99_numdig);
       $this->p99_tipo = ($this->p99_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_tipo"]:$this->p99_tipo);
       $this->p99_tipojm = ($this->p99_tipojm == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_tipojm"]:$this->p99_tipojm);
       $this->p99_numnov = ($this->p99_numnov == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_numnov"]:$this->p99_numnov);
       $this->p99_codsubrec = ($this->p99_codsubrec == ""?@$GLOBALS["HTTP_POST_VARS"]["p99_codsubrec"]:$this->p99_codsubrec);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->p99_numcgm == null ){ 
       $this->erro_sql = " Campo Numcgm nao Informado.";
       $this->erro_campo = "p99_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p99_dtoper == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "p99_dtoper_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p99_receit == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "p99_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p99_hist == null ){ 
       $this->erro_sql = " Campo Historico nao Informado.";
       $this->erro_campo = "p99_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p99_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "p99_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p99_dtvenc == null ){ 
       $this->erro_sql = " Campo Vencimento nao Informado.";
       $this->erro_campo = "p99_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p99_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "p99_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p99_numpar == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "p99_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p99_numtot == null ){ 
       $this->erro_sql = " Campo Total nao Informado.";
       $this->erro_campo = "p99_numtot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p99_numdig == null ){ 
       $this->erro_sql = " Campo Numdig nao Informado.";
       $this->erro_campo = "p99_numdig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p99_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "p99_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p99_tipojm == null ){ 
       $this->erro_sql = " Campo Tipojm nao Informado.";
       $this->erro_campo = "p99_tipojm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p99_numnov == null ){ 
       $this->erro_sql = " Campo Numnov nao Informado.";
       $this->erro_campo = "p99_numnov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p99_codsubrec == null ){ 
       $this->erro_sql = " Campo codsubrec nao Informado.";
       $this->erro_campo = "p99_codsubrec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into recibocanc(
                                       p99_numcgm 
                                      ,p99_dtoper 
                                      ,p99_receit 
                                      ,p99_hist 
                                      ,p99_valor 
                                      ,p99_dtvenc 
                                      ,p99_numpre 
                                      ,p99_numpar 
                                      ,p99_numtot 
                                      ,p99_numdig 
                                      ,p99_tipo 
                                      ,p99_tipojm 
                                      ,p99_numnov 
                                      ,p99_codsubrec 
                       )
                values (
                                $this->p99_numcgm 
                               ,".($this->p99_dtoper == "null" || $this->p99_dtoper == ""?"null":"'".$this->p99_dtoper."'")." 
                               ,$this->p99_receit 
                               ,$this->p99_hist 
                               ,$this->p99_valor 
                               ,".($this->p99_dtvenc == "null" || $this->p99_dtvenc == ""?"null":"'".$this->p99_dtvenc."'")." 
                               ,$this->p99_numpre 
                               ,$this->p99_numpar 
                               ,$this->p99_numtot 
                               ,$this->p99_numdig 
                               ,$this->p99_tipo 
                               ,$this->p99_tipojm 
                               ,$this->p99_numnov 
                               ,$this->p99_codsubrec 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cancela recibo () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cancela recibo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cancela recibo () nao Incluído. Inclusao Abortada.";
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
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update recibocanc set ";
     $virgula = "";
     if(trim($this->p99_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_numcgm"])){ 
       $sql  .= $virgula." p99_numcgm = $this->p99_numcgm ";
       $virgula = ",";
       if(trim($this->p99_numcgm) == null ){ 
         $this->erro_sql = " Campo Numcgm nao Informado.";
         $this->erro_campo = "p99_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p99_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p99_dtoper_dia"] !="") ){ 
       $sql  .= $virgula." p99_dtoper = '$this->p99_dtoper' ";
       $virgula = ",";
       if(trim($this->p99_dtoper) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "p99_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p99_dtoper_dia"])){ 
         $sql  .= $virgula." p99_dtoper = null ";
         $virgula = ",";
         if(trim($this->p99_dtoper) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "p99_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p99_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_receit"])){ 
       $sql  .= $virgula." p99_receit = $this->p99_receit ";
       $virgula = ",";
       if(trim($this->p99_receit) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "p99_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p99_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_hist"])){ 
       $sql  .= $virgula." p99_hist = $this->p99_hist ";
       $virgula = ",";
       if(trim($this->p99_hist) == null ){ 
         $this->erro_sql = " Campo Historico nao Informado.";
         $this->erro_campo = "p99_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p99_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_valor"])){ 
       $sql  .= $virgula." p99_valor = $this->p99_valor ";
       $virgula = ",";
       if(trim($this->p99_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "p99_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p99_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p99_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." p99_dtvenc = '$this->p99_dtvenc' ";
       $virgula = ",";
       if(trim($this->p99_dtvenc) == null ){ 
         $this->erro_sql = " Campo Vencimento nao Informado.";
         $this->erro_campo = "p99_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p99_dtvenc_dia"])){ 
         $sql  .= $virgula." p99_dtvenc = null ";
         $virgula = ",";
         if(trim($this->p99_dtvenc) == null ){ 
           $this->erro_sql = " Campo Vencimento nao Informado.";
           $this->erro_campo = "p99_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p99_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_numpre"])){ 
       $sql  .= $virgula." p99_numpre = $this->p99_numpre ";
       $virgula = ",";
       if(trim($this->p99_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "p99_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p99_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_numpar"])){ 
       $sql  .= $virgula." p99_numpar = $this->p99_numpar ";
       $virgula = ",";
       if(trim($this->p99_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "p99_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p99_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_numtot"])){ 
       $sql  .= $virgula." p99_numtot = $this->p99_numtot ";
       $virgula = ",";
       if(trim($this->p99_numtot) == null ){ 
         $this->erro_sql = " Campo Total nao Informado.";
         $this->erro_campo = "p99_numtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p99_numdig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_numdig"])){ 
       $sql  .= $virgula." p99_numdig = $this->p99_numdig ";
       $virgula = ",";
       if(trim($this->p99_numdig) == null ){ 
         $this->erro_sql = " Campo Numdig nao Informado.";
         $this->erro_campo = "p99_numdig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p99_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_tipo"])){ 
       $sql  .= $virgula." p99_tipo = $this->p99_tipo ";
       $virgula = ",";
       if(trim($this->p99_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "p99_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p99_tipojm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_tipojm"])){ 
       $sql  .= $virgula." p99_tipojm = $this->p99_tipojm ";
       $virgula = ",";
       if(trim($this->p99_tipojm) == null ){ 
         $this->erro_sql = " Campo Tipojm nao Informado.";
         $this->erro_campo = "p99_tipojm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p99_numnov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_numnov"])){ 
       $sql  .= $virgula." p99_numnov = $this->p99_numnov ";
       $virgula = ",";
       if(trim($this->p99_numnov) == null ){ 
         $this->erro_sql = " Campo Numnov nao Informado.";
         $this->erro_campo = "p99_numnov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p99_codsubrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p99_codsubrec"])){ 
       $sql  .= $virgula." p99_codsubrec = $this->p99_codsubrec ";
       $virgula = ",";
       if(trim($this->p99_codsubrec) == null ){ 
         $this->erro_sql = " Campo codsubrec nao Informado.";
         $this->erro_campo = "p99_codsubrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cancela recibo nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cancela recibo nao foi Alterado. Alteracao Executada.\\n";
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
     if($dbwhere==null || $dbwhere==""){
     $sql = " delete from recibocanc
                    where ";
     $sql2 = "";
       $sql2 = "oid = '$oid'";
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cancela recibo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cancela recibo nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:recibocanc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $oid = null,$campos="recibocanc.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from recibocanc ";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where recibocanc.oid = '$oid'";
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
     $sql .= " from recibocanc ";
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