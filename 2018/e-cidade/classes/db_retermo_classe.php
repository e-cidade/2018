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
//CLASSE DA ENTIDADE retermo
class cl_retermo { 
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
   var $v07_parcel = 0; 
   var $v07_dtlanc_dia = null; 
   var $v07_dtlanc_mes = null; 
   var $v07_dtlanc_ano = null; 
   var $v07_dtlanc = null; 
   var $v07_valor = 0; 
   var $v07_numpre = 0; 
   var $v07_totpar = 0; 
   var $v07_vlrpar = 0; 
   var $v07_dtvenc_dia = null; 
   var $v07_dtvenc_mes = null; 
   var $v07_dtvenc_ano = null; 
   var $v07_dtvenc = null; 
   var $v07_vlrent = 0; 
   var $v07_datpri_dia = null; 
   var $v07_datpri_mes = null; 
   var $v07_datpri_ano = null; 
   var $v07_datpri = null; 
   var $v07_vlrmul = 0; 
   var $v07_vlrjur = 0; 
   var $v07_perjur = 0; 
   var $v07_permul = 0; 
   var $v07_login = null; 
   var $v07_mtermo = 0; 
   var $v07_numcgm = 0; 
   var $v07_hist = null; 
   var $v07_ultpar = 0; 
   var $v07_desconto = 0; 
   var $v07_descjur = 0; 
   var $v07_descmul = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v07_parcel = int4 = Parcelamento 
                 v07_dtlanc = date = data de lancamento do parcelamento 
                 v07_valor = float8 = valor do parcelamento 
                 v07_numpre = int4 = numpre do parcelamento 
                 v07_totpar = int4 = total de parcelas 
                 v07_vlrpar = float8 = valor das parcelas 
                 v07_dtvenc = date = data de vencimento 
                 v07_vlrent = float8 = valor da entrada 
                 v07_datpri = date = data da primeira parcela 
                 v07_vlrmul = float8 = valor da multa 
                 v07_vlrjur = float8 = valor dos juros 
                 v07_perjur = float8 = percentual dos juros 
                 v07_permul = float8 = percentual das multas 
                 v07_login = varchar(8) = login 
                 v07_mtermo = oid = termo 
                 v07_numcgm = int4 = Responsável pelo parcelamento 
                 v07_hist = varchar(130) = historico 
                 v07_ultpar = float8 = Valor da ultima parcela 
                 v07_desconto = int4 = Código do desconto 
                 v07_descjur = float8 = Desconto nos juros 
                 v07_descmul = float8 = Desconto na multa 
                 ";
   //funcao construtor da classe 
   function cl_retermo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("retermo"); 
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
       $this->v07_parcel = ($this->v07_parcel == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_parcel"]:$this->v07_parcel);
       if($this->v07_dtlanc == ""){
         $this->v07_dtlanc_dia = ($this->v07_dtlanc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_dtlanc_dia"]:$this->v07_dtlanc_dia);
         $this->v07_dtlanc_mes = ($this->v07_dtlanc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_dtlanc_mes"]:$this->v07_dtlanc_mes);
         $this->v07_dtlanc_ano = ($this->v07_dtlanc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_dtlanc_ano"]:$this->v07_dtlanc_ano);
         if($this->v07_dtlanc_dia != ""){
            $this->v07_dtlanc = $this->v07_dtlanc_ano."-".$this->v07_dtlanc_mes."-".$this->v07_dtlanc_dia;
         }
       }
       $this->v07_valor = ($this->v07_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_valor"]:$this->v07_valor);
       $this->v07_numpre = ($this->v07_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_numpre"]:$this->v07_numpre);
       $this->v07_totpar = ($this->v07_totpar == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_totpar"]:$this->v07_totpar);
       $this->v07_vlrpar = ($this->v07_vlrpar == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_vlrpar"]:$this->v07_vlrpar);
       if($this->v07_dtvenc == ""){
         $this->v07_dtvenc_dia = ($this->v07_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_dtvenc_dia"]:$this->v07_dtvenc_dia);
         $this->v07_dtvenc_mes = ($this->v07_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_dtvenc_mes"]:$this->v07_dtvenc_mes);
         $this->v07_dtvenc_ano = ($this->v07_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_dtvenc_ano"]:$this->v07_dtvenc_ano);
         if($this->v07_dtvenc_dia != ""){
            $this->v07_dtvenc = $this->v07_dtvenc_ano."-".$this->v07_dtvenc_mes."-".$this->v07_dtvenc_dia;
         }
       }
       $this->v07_vlrent = ($this->v07_vlrent == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_vlrent"]:$this->v07_vlrent);
       if($this->v07_datpri == ""){
         $this->v07_datpri_dia = ($this->v07_datpri_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_datpri_dia"]:$this->v07_datpri_dia);
         $this->v07_datpri_mes = ($this->v07_datpri_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_datpri_mes"]:$this->v07_datpri_mes);
         $this->v07_datpri_ano = ($this->v07_datpri_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_datpri_ano"]:$this->v07_datpri_ano);
         if($this->v07_datpri_dia != ""){
            $this->v07_datpri = $this->v07_datpri_ano."-".$this->v07_datpri_mes."-".$this->v07_datpri_dia;
         }
       }
       $this->v07_vlrmul = ($this->v07_vlrmul == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_vlrmul"]:$this->v07_vlrmul);
       $this->v07_vlrjur = ($this->v07_vlrjur == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_vlrjur"]:$this->v07_vlrjur);
       $this->v07_perjur = ($this->v07_perjur == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_perjur"]:$this->v07_perjur);
       $this->v07_permul = ($this->v07_permul == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_permul"]:$this->v07_permul);
       $this->v07_login = ($this->v07_login == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_login"]:$this->v07_login);
       $this->v07_mtermo = ($this->v07_mtermo == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_mtermo"]:$this->v07_mtermo);
       $this->v07_numcgm = ($this->v07_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_numcgm"]:$this->v07_numcgm);
       $this->v07_hist = ($this->v07_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_hist"]:$this->v07_hist);
       $this->v07_ultpar = ($this->v07_ultpar == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_ultpar"]:$this->v07_ultpar);
       $this->v07_desconto = ($this->v07_desconto == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_desconto"]:$this->v07_desconto);
       $this->v07_descjur = ($this->v07_descjur == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_descjur"]:$this->v07_descjur);
       $this->v07_descmul = ($this->v07_descmul == ""?@$GLOBALS["HTTP_POST_VARS"]["v07_descmul"]:$this->v07_descmul);
     }else{
     }
   }
   // funcao para inclusao
   function incluir (){ 
      $this->atualizacampos();
     if($this->v07_parcel == null ){ 
       $this->erro_sql = " Campo Parcelamento nao Informado.";
       $this->erro_campo = "v07_parcel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_dtlanc == null ){ 
       $this->erro_sql = " Campo data de lancamento do parcelamento nao Informado.";
       $this->erro_campo = "v07_dtlanc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_valor == null ){ 
       $this->erro_sql = " Campo valor do parcelamento nao Informado.";
       $this->erro_campo = "v07_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_numpre == null ){ 
       $this->erro_sql = " Campo numpre do parcelamento nao Informado.";
       $this->erro_campo = "v07_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_totpar == null ){ 
       $this->erro_sql = " Campo total de parcelas nao Informado.";
       $this->erro_campo = "v07_totpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_vlrpar == null ){ 
       $this->erro_sql = " Campo valor das parcelas nao Informado.";
       $this->erro_campo = "v07_vlrpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_dtvenc == null ){ 
       $this->erro_sql = " Campo data de vencimento nao Informado.";
       $this->erro_campo = "v07_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_vlrent == null ){ 
       $this->erro_sql = " Campo valor da entrada nao Informado.";
       $this->erro_campo = "v07_vlrent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_datpri == null ){ 
       $this->erro_sql = " Campo data da primeira parcela nao Informado.";
       $this->erro_campo = "v07_datpri_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_vlrmul == null ){ 
       $this->erro_sql = " Campo valor da multa nao Informado.";
       $this->erro_campo = "v07_vlrmul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_vlrjur == null ){ 
       $this->erro_sql = " Campo valor dos juros nao Informado.";
       $this->erro_campo = "v07_vlrjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_perjur == null ){ 
       $this->erro_sql = " Campo percentual dos juros nao Informado.";
       $this->erro_campo = "v07_perjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_permul == null ){ 
       $this->erro_sql = " Campo percentual das multas nao Informado.";
       $this->erro_campo = "v07_permul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_login == null ){ 
       $this->erro_sql = " Campo login nao Informado.";
       $this->erro_campo = "v07_login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_mtermo == null ){ 
       $this->erro_sql = " Campo termo nao Informado.";
       $this->erro_campo = "v07_mtermo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_numcgm == null ){ 
       $this->erro_sql = " Campo Responsável pelo parcelamento nao Informado.";
       $this->erro_campo = "v07_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_hist == null ){ 
       $this->erro_sql = " Campo historico nao Informado.";
       $this->erro_campo = "v07_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_ultpar == null ){ 
       $this->erro_sql = " Campo Valor da ultima parcela nao Informado.";
       $this->erro_campo = "v07_ultpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_desconto == null ){ 
       $this->erro_sql = " Campo Código do desconto nao Informado.";
       $this->erro_campo = "v07_desconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_descjur == null ){ 
       $this->erro_sql = " Campo Desconto nos juros nao Informado.";
       $this->erro_campo = "v07_descjur";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v07_descmul == null ){ 
       $this->erro_sql = " Campo Desconto na multa nao Informado.";
       $this->erro_campo = "v07_descmul";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into retermo(
                                       v07_parcel 
                                      ,v07_dtlanc 
                                      ,v07_valor 
                                      ,v07_numpre 
                                      ,v07_totpar 
                                      ,v07_vlrpar 
                                      ,v07_dtvenc 
                                      ,v07_vlrent 
                                      ,v07_datpri 
                                      ,v07_vlrmul 
                                      ,v07_vlrjur 
                                      ,v07_perjur 
                                      ,v07_permul 
                                      ,v07_login 
                                      ,v07_mtermo 
                                      ,v07_numcgm 
                                      ,v07_hist 
                                      ,v07_ultpar 
                                      ,v07_desconto 
                                      ,v07_descjur 
                                      ,v07_descmul 
                       )
                values (
                                $this->v07_parcel 
                               ,".($this->v07_dtlanc == "null" || $this->v07_dtlanc == ""?"null":"'".$this->v07_dtlanc."'")." 
                               ,$this->v07_valor 
                               ,$this->v07_numpre 
                               ,$this->v07_totpar 
                               ,$this->v07_vlrpar 
                               ,".($this->v07_dtvenc == "null" || $this->v07_dtvenc == ""?"null":"'".$this->v07_dtvenc."'")." 
                               ,$this->v07_vlrent 
                               ,".($this->v07_datpri == "null" || $this->v07_datpri == ""?"null":"'".$this->v07_datpri."'")." 
                               ,$this->v07_vlrmul 
                               ,$this->v07_vlrjur 
                               ,$this->v07_perjur 
                               ,$this->v07_permul 
                               ,'$this->v07_login' 
                               ,$this->v07_mtermo 
                               ,$this->v07_numcgm 
                               ,'$this->v07_hist' 
                               ,$this->v07_ultpar 
                               ,$this->v07_desconto 
                               ,$this->v07_descjur 
                               ,$this->v07_descmul 
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
   function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update retermo set ";
     $virgula = "";
     if(trim($this->v07_parcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_parcel"])){ 
       $sql  .= $virgula." v07_parcel = $this->v07_parcel ";
       $virgula = ",";
       if(trim($this->v07_parcel) == null ){ 
         $this->erro_sql = " Campo Parcelamento nao Informado.";
         $this->erro_campo = "v07_parcel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_dtlanc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_dtlanc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v07_dtlanc_dia"] !="") ){ 
       $sql  .= $virgula." v07_dtlanc = '$this->v07_dtlanc' ";
       $virgula = ",";
       if(trim($this->v07_dtlanc) == null ){ 
         $this->erro_sql = " Campo data de lancamento do parcelamento nao Informado.";
         $this->erro_campo = "v07_dtlanc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v07_dtlanc_dia"])){ 
         $sql  .= $virgula." v07_dtlanc = null ";
         $virgula = ",";
         if(trim($this->v07_dtlanc) == null ){ 
           $this->erro_sql = " Campo data de lancamento do parcelamento nao Informado.";
           $this->erro_campo = "v07_dtlanc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v07_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_valor"])){ 
       $sql  .= $virgula." v07_valor = $this->v07_valor ";
       $virgula = ",";
       if(trim($this->v07_valor) == null ){ 
         $this->erro_sql = " Campo valor do parcelamento nao Informado.";
         $this->erro_campo = "v07_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_numpre"])){ 
       $sql  .= $virgula." v07_numpre = $this->v07_numpre ";
       $virgula = ",";
       if(trim($this->v07_numpre) == null ){ 
         $this->erro_sql = " Campo numpre do parcelamento nao Informado.";
         $this->erro_campo = "v07_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_totpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_totpar"])){ 
       $sql  .= $virgula." v07_totpar = $this->v07_totpar ";
       $virgula = ",";
       if(trim($this->v07_totpar) == null ){ 
         $this->erro_sql = " Campo total de parcelas nao Informado.";
         $this->erro_campo = "v07_totpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_vlrpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrpar"])){ 
       $sql  .= $virgula." v07_vlrpar = $this->v07_vlrpar ";
       $virgula = ",";
       if(trim($this->v07_vlrpar) == null ){ 
         $this->erro_sql = " Campo valor das parcelas nao Informado.";
         $this->erro_campo = "v07_vlrpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v07_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." v07_dtvenc = '$this->v07_dtvenc' ";
       $virgula = ",";
       if(trim($this->v07_dtvenc) == null ){ 
         $this->erro_sql = " Campo data de vencimento nao Informado.";
         $this->erro_campo = "v07_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v07_dtvenc_dia"])){ 
         $sql  .= $virgula." v07_dtvenc = null ";
         $virgula = ",";
         if(trim($this->v07_dtvenc) == null ){ 
           $this->erro_sql = " Campo data de vencimento nao Informado.";
           $this->erro_campo = "v07_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v07_vlrent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrent"])){ 
       $sql  .= $virgula." v07_vlrent = $this->v07_vlrent ";
       $virgula = ",";
       if(trim($this->v07_vlrent) == null ){ 
         $this->erro_sql = " Campo valor da entrada nao Informado.";
         $this->erro_campo = "v07_vlrent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_datpri)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_datpri_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v07_datpri_dia"] !="") ){ 
       $sql  .= $virgula." v07_datpri = '$this->v07_datpri' ";
       $virgula = ",";
       if(trim($this->v07_datpri) == null ){ 
         $this->erro_sql = " Campo data da primeira parcela nao Informado.";
         $this->erro_campo = "v07_datpri_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v07_datpri_dia"])){ 
         $sql  .= $virgula." v07_datpri = null ";
         $virgula = ",";
         if(trim($this->v07_datpri) == null ){ 
           $this->erro_sql = " Campo data da primeira parcela nao Informado.";
           $this->erro_campo = "v07_datpri_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v07_vlrmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrmul"])){ 
       $sql  .= $virgula." v07_vlrmul = $this->v07_vlrmul ";
       $virgula = ",";
       if(trim($this->v07_vlrmul) == null ){ 
         $this->erro_sql = " Campo valor da multa nao Informado.";
         $this->erro_campo = "v07_vlrmul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_vlrjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_vlrjur"])){ 
       $sql  .= $virgula." v07_vlrjur = $this->v07_vlrjur ";
       $virgula = ",";
       if(trim($this->v07_vlrjur) == null ){ 
         $this->erro_sql = " Campo valor dos juros nao Informado.";
         $this->erro_campo = "v07_vlrjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_perjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_perjur"])){ 
       $sql  .= $virgula." v07_perjur = $this->v07_perjur ";
       $virgula = ",";
       if(trim($this->v07_perjur) == null ){ 
         $this->erro_sql = " Campo percentual dos juros nao Informado.";
         $this->erro_campo = "v07_perjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_permul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_permul"])){ 
       $sql  .= $virgula." v07_permul = $this->v07_permul ";
       $virgula = ",";
       if(trim($this->v07_permul) == null ){ 
         $this->erro_sql = " Campo percentual das multas nao Informado.";
         $this->erro_campo = "v07_permul";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_login"])){ 
       $sql  .= $virgula." v07_login = '$this->v07_login' ";
       $virgula = ",";
       if(trim($this->v07_login) == null ){ 
         $this->erro_sql = " Campo login nao Informado.";
         $this->erro_campo = "v07_login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_mtermo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_mtermo"])){ 
       $sql  .= $virgula." v07_mtermo = $this->v07_mtermo ";
       $virgula = ",";
       if(trim($this->v07_mtermo) == null ){ 
         $this->erro_sql = " Campo termo nao Informado.";
         $this->erro_campo = "v07_mtermo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_numcgm"])){ 
       $sql  .= $virgula." v07_numcgm = $this->v07_numcgm ";
       $virgula = ",";
       if(trim($this->v07_numcgm) == null ){ 
         $this->erro_sql = " Campo Responsável pelo parcelamento nao Informado.";
         $this->erro_campo = "v07_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_hist"])){ 
       $sql  .= $virgula." v07_hist = '$this->v07_hist' ";
       $virgula = ",";
       if(trim($this->v07_hist) == null ){ 
         $this->erro_sql = " Campo historico nao Informado.";
         $this->erro_campo = "v07_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_ultpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_ultpar"])){ 
       $sql  .= $virgula." v07_ultpar = $this->v07_ultpar ";
       $virgula = ",";
       if(trim($this->v07_ultpar) == null ){ 
         $this->erro_sql = " Campo Valor da ultima parcela nao Informado.";
         $this->erro_campo = "v07_ultpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_desconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_desconto"])){ 
       $sql  .= $virgula." v07_desconto = $this->v07_desconto ";
       $virgula = ",";
       if(trim($this->v07_desconto) == null ){ 
         $this->erro_sql = " Campo Código do desconto nao Informado.";
         $this->erro_campo = "v07_desconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_descjur)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_descjur"])){ 
       $sql  .= $virgula." v07_descjur = $this->v07_descjur ";
       $virgula = ",";
       if(trim($this->v07_descjur) == null ){ 
         $this->erro_sql = " Campo Desconto nos juros nao Informado.";
         $this->erro_campo = "v07_descjur";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v07_descmul)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v07_descmul"])){ 
       $sql  .= $virgula." v07_descmul = $this->v07_descmul ";
       $virgula = ",";
       if(trim($this->v07_descmul) == null ){ 
         $this->erro_sql = " Campo Desconto na multa nao Informado.";
         $this->erro_campo = "v07_descmul";
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
     $sql = " delete from retermo
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
        $this->erro_sql   = "Record Vazio na Tabela:retermo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>