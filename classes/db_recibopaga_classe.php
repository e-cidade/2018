<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: Caixa
//CLASSE DA ENTIDADE recibopaga
class cl_recibopaga { 
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
   var $k00_numpre = 0; 
   var $k00_numpar = 0; 
   var $k00_numtot = 0; 
   var $k00_numdig = 0; 
   var $k00_conta = 0; 
   var $k00_dtpaga_dia = null; 
   var $k00_dtpaga_mes = null; 
   var $k00_dtpaga_ano = null; 
   var $k00_dtpaga = null; 
   var $k00_numnov = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k00_numcgm = int4 = cgm 
                 k00_dtoper = date = DT.Lanc 
                 k00_receit = int4 = Receita 
                 k00_hist = int4 = Histórico de Cálculo 
                 k00_valor = float8 = Valor 
                 k00_dtvenc = date = DT.Venc 
                 k00_numpre = int4 = Numpre 
                 k00_numpar = int4 = Parcela 
                 k00_numtot = int4 = Tot 
                 k00_numdig = int4 = D
                 k00_conta = int4 = Conta 
                 k00_dtpaga = date = Data do pagamento 
                 k00_numnov = int4 = Codigo Auxiliar 
                 ";
   //funcao construtor da classe 
   function cl_recibopaga() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("recibopaga"); 
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
       $this->k00_numpre = ($this->k00_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpre"]:$this->k00_numpre);
       $this->k00_numpar = ($this->k00_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numpar"]:$this->k00_numpar);
       $this->k00_numtot = ($this->k00_numtot == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numtot"]:$this->k00_numtot);
       $this->k00_numdig = ($this->k00_numdig == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numdig"]:$this->k00_numdig);
       $this->k00_conta = ($this->k00_conta == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_conta"]:$this->k00_conta);
       if($this->k00_dtpaga == ""){
         $this->k00_dtpaga_dia = ($this->k00_dtpaga_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_dia"]:$this->k00_dtpaga_dia);
         $this->k00_dtpaga_mes = ($this->k00_dtpaga_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_mes"]:$this->k00_dtpaga_mes);
         $this->k00_dtpaga_ano = ($this->k00_dtpaga_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_ano"]:$this->k00_dtpaga_ano);
         if($this->k00_dtpaga_dia != ""){
            $this->k00_dtpaga = $this->k00_dtpaga_ano."-".$this->k00_dtpaga_mes."-".$this->k00_dtpaga_dia;
         }
       }
       $this->k00_numnov = ($this->k00_numnov == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_numnov"]:$this->k00_numnov);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
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
     if($this->k00_numtot == null ){ 
       $this->erro_sql = " Campo Total de Parcelas nao Informado.";
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
     if($this->k00_conta == null ){ 
       $this->erro_sql = " Campo Conta nao Informado.";
       $this->erro_campo = "k00_conta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_dtpaga == null ){ 
       $this->k00_dtpaga = "null";
     }
     if($this->k00_numnov == null ){ 
       $this->erro_sql = " Campo Codigo Auxiliar nao Informado.";
       $this->erro_campo = "k00_numnov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into recibopaga(
                                       k00_numcgm 
                                      ,k00_dtoper 
                                      ,k00_receit 
                                      ,k00_hist 
                                      ,k00_valor 
                                      ,k00_dtvenc 
                                      ,k00_numpre 
                                      ,k00_numpar 
                                      ,k00_numtot 
                                      ,k00_numdig 
                                      ,k00_conta 
                                      ,k00_dtpaga 
                                      ,k00_numnov 
                       )
                values (
                                $this->k00_numcgm 
                               ,".($this->k00_dtoper == "null" || $this->k00_dtoper == ""?"null":"'".$this->k00_dtoper."'")." 
                               ,$this->k00_receit 
                               ,$this->k00_hist 
                               ,$this->k00_valor 
                               ,".($this->k00_dtvenc == "null" || $this->k00_dtvenc == ""?"null":"'".$this->k00_dtvenc."'")." 
                               ,$this->k00_numpre 
                               ,$this->k00_numpar 
                               ,$this->k00_numtot 
                               ,$this->k00_numdig 
                               ,$this->k00_conta 
                               ,".($this->k00_dtpaga == "null" || $this->k00_dtpaga == ""?"null":"'".$this->k00_dtpaga."'")." 
                               ,$this->k00_numnov 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pagamento Recibo () nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pagamento Recibo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pagamento Recibo () nao Incluído. Inclusao Abortada.";
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
   function alterar ( $oid=null, $dbwhere=null) { 
      $this->atualizacampos();
     $sql = " update recibopaga set ";
     $virgula = "";
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
     if(trim($this->k00_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numtot"])){ 
       $sql  .= $virgula." k00_numtot = $this->k00_numtot ";
       $virgula = ",";
       if(trim($this->k00_numtot) == null ){ 
         $this->erro_sql = " Campo Total de Parcelas nao Informado.";
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
     if(trim($this->k00_conta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_conta"])){ 
       $sql  .= $virgula." k00_conta = $this->k00_conta ";
       $virgula = ",";
       if(trim($this->k00_conta) == null ){ 
         $this->erro_sql = " Campo Conta nao Informado.";
         $this->erro_campo = "k00_conta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if( (trim($this->k00_dtpaga) != "null" && trim($this->k00_dtpaga)!="") || isset($GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_dia"] !="") ){ 
       $sql  .= $virgula." k00_dtpaga = '$this->k00_dtpaga' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k00_dtpaga_dia"]) || $this->k00_dtpaga == "null"){ 
         $sql  .= $virgula." k00_dtpaga = null ";
         $virgula = ",";
       }
     }
     if(trim($this->k00_numnov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_numnov"])){ 
       $sql  .= $virgula." k00_numnov = $this->k00_numnov ";
       $virgula = ",";
       if(trim($this->k00_numnov) == null ){ 
         $this->erro_sql = " Campo Codigo Auxiliar nao Informado.";
         $this->erro_campo = "k00_numnov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($dbwhere==null || $dbwhere ==""){
       $sql .= "oid = '$oid'";
     }else{
       $sql .= $dbwhere;
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pagamento Recibo nao Alterado. Alteracao Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pagamento Recibo nao foi Alterado. Alteracao Executada.\\n";
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
     $sql = " delete from recibopaga
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
       $this->erro_sql   = "Pagamento Recibo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pagamento Recibo nao Encontrado. Exclusão não Efetuada.\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:recibopaga";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $oid = null,$campos="recibopaga.oid,*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from recibopaga ";
     $sql .= "      inner join histcalc  on  histcalc.k01_codigo = recibopaga.k00_hist";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = recibopaga.k00_receit";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql2 = "";
     if($dbwhere==""){
       if( $oid != "" && $oid != null){
          $sql2 = " where recibopaga.oid = '$oid'";
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
     $sql .= " from recibopaga ";
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

   function sql_query_dadosRecibo($iNumpreRecibo) {
     
     $sSql = "select distinct 
                     recibopagaboleto.k138_numnov as numpre_recibo,
                     recibopagaboleto.k138_data   as data_emissao,
                     recibopaga.k00_dtpaga        as data_vencimento,
                     arrebanco.k00_numbco         as nosso_numero ,
                     arrepaga.k00_dtpaga          as data_pagamento
                from recibopagaboleto
                     inner join arrebanco    on arrebanco.k00_numpre  = recibopagaboleto.k138_numnov
                     inner join recibopaga   on recibopaga.k00_numnov = recibopagaboleto.k138_numnov                     
                     left  join arrepaga     on arrepaga.k00_numpre   = recibopaga.k00_numpre 
                                            and arrepaga.k00_numpar   = recibopaga.k00_numpar  
               where k138_numnov = {$iNumpreRecibo}                                                  ";
     
     return $sSql;
     
   }
   

  function sql_query_descontoConced_cotaUnica($iRegra = 0, $iAno = null, $dDataInicial = null, $dDataFinal = null) {
   
    $sSql  = "select matricula,                                                                                   \n";
    $sSql .= "       contribuinte,                                                                                \n";
    $sSql .= "       receita,                                                                                     \n";
    $sSql .= "       descricao,                                                                                   \n";
    $sSql .= "       k02_estorc,                                                                                  \n";
    $sSql .= "       vlrcalculado,                                                                                \n";
    $sSql .= "       abs(sum(abs(vlrpago) - abs(vlrdesconto))) as vlrpago,                                        \n";
    $sSql .= "       case when minhist <> 918                                                                     \n";
    $sSql .= "              then vlrcalculado - abs(sum(abs(vlrdesconto)))                                        \n";
    $sSql .= "              else abs(sum(abs(vlrdesconto)))                                                       \n";
    $sSql .= "       end as vlrdesconto,                                                                          \n";
    $sSql .= "       qtd                                                                                          \n";
    $sSql .= "  from (select matricula,                                                                           \n";
    $sSql .= "               contribuinte,                                                                        \n";
    $sSql .= "               receita,                                                                             \n";
    $sSql .= "               descricao,                                                                           \n";
    $sSql .= "               k02_estorc,                                                                          \n";
    $sSql .= "               vlrcalculado,                                                                        \n";
    $sSql .= "               sum(abs(vlrpago))     as vlrpago,                                                    \n";
    $sSql .= "               sum(abs(vlrdesconto)) as vlrdesconto,                                                \n";
    $sSql .= "               minhist,                                                                             \n";
    $sSql .= "               qtd                                                                                  \n";
    $sSql .= "          from (select x.j21_matric as matricula,                                                   \n";
    $sSql .= "                       (select rvNome                                                               \n";
    $sSql .= "                          from fc_busca_envolvidos(true, {$iRegra}, 'M', x.j21_matric) limit 1      \n";
    $sSql .= "                       ) as contribuinte,                                                           \n";
    $sSql .= "                       x.j21_receit         as receita,                                             \n";
    $sSql .= "                       tabrec.k02_descr     as descricao,                                           \n";
    $sSql .= "                       taborc.k02_estorc,                                                           \n";
    $sSql .= "                       round(x.j21_valor,2) as vlrcalculado,                                        \n";
    $sSql .= "                       round((case when arrepaga.k00_hist in (990, 918)                             \n";
    $sSql .= "                                     then 0 else arrepaga.k00_valor                                 \n";
    $sSql .= "                              end), 2) as vlrpago,                                                  \n";
    $sSql .= "                       round((case when arrepaga.k00_hist not in (990, 918)                         \n";
    $sSql .= "                                     then 0 else arrepaga.k00_valor                                 \n";
    $sSql .= "                              end), 2) as vlrdesconto,                                              \n";
    $sSql .= "                       (select *                                                                    \n";
    $sSql .= "                          from fc_consultadescontounica(arrepaga.k00_numpre)) as qtd,               \n";
    $sSql .= "                                (select min(k00_hist)                                               \n";
    $sSql .= "                                    from arrepaga as x                                              \n";
    $sSql .= "                                   where x.k00_numpre = arrepaga.k00_numpre                         \n";
    $sSql .= "                                     and x.k00_hist in (990, 918)                                   \n";
    $sSql .= "                                ) as minhist                                                        \n";
    $sSql .= "                  from (select iptucalv.j21_matric,                                                 \n";
    $sSql .= "                               iptucalv.j21_receit,                                                 \n";
    $sSql .= "                               iptucalv.j21_anousu,                                                 \n";
    $sSql .= "                               round(sum(iptucalv.j21_valor), 2) as j21_valor                       \n";
    $sSql .= "                          from iptucalv                                                             \n";
    $sSql .= "                         where iptucalv.j21_anousu = {$iAno}                                        \n";
    $sSql .= "                         group by iptucalv.j21_matric,                                              \n";
    $sSql .= "                                  iptucalv.j21_receit,                                              \n";
    $sSql .= "                                  iptucalv.j21_anousu                                               \n";
    $sSql .= "                       ) as x                                                                       \n";
    $sSql .= "                       inner join iptunump  on iptunump.j20_matric   = x.j21_matric                 \n";
    $sSql .= "                                           and iptunump.j20_anousu   = x.j21_anousu                 \n";
    $sSql .= "                       inner join arrepaga  on arrepaga.k00_numpre   = iptunump.j20_numpre          \n";
    $sSql .= "                                           and arrepaga.k00_receit   = x.j21_receit                 \n";
    $sSql .= "                       inner join tabrec    on tabrec.k02_codigo     = x.j21_receit                 \n";
    $sSql .= "                       inner join taborc    on taborc.k02_codigo     = tabrec.k02_codigo            \n";
    $sSql .= "                                              and taborc.k02_anousu     = {$iAno}                   \n";
    $sSql .= "                 where j21_anousu = {$iAno}                                                         \n";
    $sSql .= "                   and arrepaga.k00_dtpaga between '{$dDataInicial}' and '{$dDataFinal}'            \n";
    $sSql .= "                   and exists     (select 1                                                         \n";
    $sSql .= "                                     from recibounica                                               \n";
    $sSql .= "                                    where recibounica.k00_numpre = arrepaga.k00_numpre)             \n";
    $sSql .= "                   and not exists (select 1                                                         \n";
    $sSql .= "                                     from arrecad                                                   \n";
    $sSql .= "                                    where arrecad.k00_numpre = arrepaga.k00_numpre)                 \n";
    $sSql .= "               ) as x                                                                               \n";
    $sSql .= "         group by matricula, contribuinte, receita,                                                 \n";
    $sSql .= "                  descricao, k02_estorc, vlrcalculado, qtd, minhist                                 \n";
    $sSql .= "       ) as z                                                                                       \n";
    $sSql .= " where qtd > 0                                                                                      \n";
    $sSql .= " group by matricula, contribuinte, receita, descricao,  k02_estorc, vlrcalculado, qtd, minhist      \n";
    $sSql .= " order by receita, matricula                                                                        \n";
    
    return $sSql;
    
  }
   
   
   
}
?>